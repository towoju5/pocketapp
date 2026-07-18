<?php

namespace App\Http\Controllers;

use App\Models\P2pOffer;
use App\Models\P2pTrade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class P2pTradeController extends Controller
{
    public function index(): View
    {
        $trades = P2pTrade::with(['offer', 'maker', 'taker'])
            ->where('maker_id', auth()->id())
            ->orWhere('taker_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('p2p.trades.index', compact('trades'));
    }

    public function store(Request $request, P2pOffer $offer): RedirectResponse
    {
        abort_if($offer->user_id === auth()->id(), 403, 'You cannot take your own offer.');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);
        $amount = (float) $validated['amount'];

        if ($amount < $offer->min_limit || $amount > $offer->max_limit) {
            return back()->with('error', 'Amount is outside this offer\'s limits.');
        }

        $trade = DB::transaction(function () use ($offer, $amount) {
            $lockedOffer = P2pOffer::where('id', $offer->id)->lockForUpdate()->first();

            if ($lockedOffer->status !== 'active' || $lockedOffer->available_amount < $amount) {
                return null;
            }

            $isMakerSelling = $lockedOffer->type === 'sell';
            $sellerId = $isMakerSelling ? $lockedOffer->user_id : auth()->id();
            $buyerId = $isMakerSelling ? auth()->id() : $lockedOffer->user_id;

            $seller = \App\Models\User::find($sellerId);
            $wallet = $seller->getWallet($lockedOffer->wallet_slug);

            if (! $wallet || ! $wallet->withdraw($amount, ['description' => "P2P escrow for offer #{$lockedOffer->id}"])) {
                return null;
            }

            $lockedOffer->decrement('available_amount', $amount);

            return P2pTrade::create([
                'offer_id' => $lockedOffer->id,
                'maker_id' => $lockedOffer->user_id,
                'taker_id' => auth()->id(),
                'seller_id' => $sellerId,
                'buyer_id' => $buyerId,
                'amount' => $amount,
                'fiat_amount' => $amount * $lockedOffer->price_per_unit,
                'wallet_slug' => $lockedOffer->wallet_slug,
                'status' => 'pending_payment',
                'payment_deadline' => now()->addMinutes(30),
            ]);
        });

        if (! $trade) {
            return back()->with('error', 'This offer no longer has enough available amount, or the seller has insufficient balance.');
        }

        return redirect()->route('p2p-trades.show', $trade)->with('success', 'Trade opened. Escrow is locked pending payment.');
    }

    public function show(P2pTrade $p2pTrade): View
    {
        $this->authorizeParty($p2pTrade);

        return view('p2p.trades.show', ['trade' => $p2pTrade]);
    }

    public function pay(Request $request, P2pTrade $p2pTrade): RedirectResponse
    {
        abort_unless($p2pTrade->buyer_id === auth()->id(), 403);

        if ($p2pTrade->status !== 'pending_payment') {
            return back()->with('error', 'This trade is not awaiting payment.');
        }

        $request->validate([
            'payment_proof' => ['required', 'file', 'image', 'max:5120'],
        ]);

        $path = $request->file('payment_proof')->store('p2p/' . $p2pTrade->id, 'public');

        $p2pTrade->update([
            'payment_proof_path' => $path,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment proof uploaded. Waiting for the seller to release escrow.');
    }

    public function release(P2pTrade $p2pTrade): RedirectResponse
    {
        abort_unless($p2pTrade->seller_id === auth()->id(), 403);

        if ($p2pTrade->status !== 'paid') {
            return back()->with('error', 'This trade is not ready to be released.');
        }

        $p2pTrade->buyer->getWallet($p2pTrade->wallet_slug)
            ->deposit($p2pTrade->amount, ['description' => "P2P trade #{$p2pTrade->id} released"]);

        $p2pTrade->update(['status' => 'released', 'released_at' => now()]);

        return back()->with('success', 'Escrow released to the buyer.');
    }

    public function cancel(P2pTrade $p2pTrade): RedirectResponse
    {
        $this->authorizeParty($p2pTrade);

        if ($p2pTrade->status !== 'pending_payment') {
            return back()->with('error', 'Only trades awaiting payment can be cancelled.');
        }

        $p2pTrade->seller->getWallet($p2pTrade->wallet_slug)
            ->deposit($p2pTrade->amount, ['description' => "P2P trade #{$p2pTrade->id} cancelled, escrow refunded"]);

        $p2pTrade->offer()->increment('available_amount', $p2pTrade->amount);

        $p2pTrade->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return back()->with('success', 'Trade cancelled and escrow refunded to the seller.');
    }

    public function dispute(Request $request, P2pTrade $p2pTrade): RedirectResponse
    {
        $this->authorizeParty($p2pTrade);

        if (! in_array($p2pTrade->status, ['pending_payment', 'paid'])) {
            return back()->with('error', 'This trade cannot be disputed.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $p2pTrade->update([
            'status' => 'disputed',
            'dispute_reason' => $validated['reason'],
            'disputed_by' => auth()->id(),
            'disputed_at' => now(),
        ]);

        return back()->with('success', 'Dispute raised. An administrator will review this trade.');
    }

    public function undoDispute(P2pTrade $p2pTrade): RedirectResponse
    {
        $this->authorizeParty($p2pTrade);

        if ($p2pTrade->status !== 'disputed') {
            return back()->with('error', 'This trade is not currently disputed.');
        }

        $p2pTrade->update([
            'status' => $p2pTrade->paid_at ? 'paid' : 'pending_payment',
            'dispute_reason' => null,
            'disputed_by' => null,
            'disputed_at' => null,
        ]);

        return back()->with('success', 'Dispute withdrawn.');
    }

    private function authorizeParty(P2pTrade $trade): void
    {
        abort_unless(in_array(auth()->id(), [$trade->maker_id, $trade->taker_id]), 403);
    }
}
