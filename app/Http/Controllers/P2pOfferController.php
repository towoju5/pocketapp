<?php

namespace App\Http\Controllers;

use App\Models\P2pOffer;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class P2pOfferController extends Controller
{
    public function index(Request $request): View
    {
        $offers = P2pOffer::with('maker')
            ->where('status', 'active')
            ->where('user_id', '!=', auth()->id())
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->sell_currency, fn ($q) => $q->where('sell_currency', $request->sell_currency))
            ->when($request->buy_currency, fn ($q) => $q->where('buy_currency', $request->buy_currency))
            ->latest()
            ->paginate(15);

        $myOffers = P2pOffer::where('user_id', auth()->id())->latest()->get();
        $currencies = p2p_currencies();

        return view('p2p.offers.index', compact('offers', 'myOffers', 'currencies'));
    }

    public function create(): View
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->orderBy('name')->get();
        $currencies = p2p_currencies();

        return view('p2p.offers.create', compact('paymentMethods', 'currencies'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currencies = p2p_currencies();

        $validated = $request->validate([
            'type' => ['required', 'in:buy,sell'],
            'sell_currency' => ['required', 'string', 'in:' . implode(',', $currencies), 'different:buy_currency'],
            'buy_currency' => ['required', 'string', 'in:' . implode(',', $currencies)],
            'price_per_unit' => ['required', 'numeric', 'min:0.01'],
            'min_limit' => ['required', 'numeric', 'min:0.01'],
            'max_limit' => ['required', 'numeric', 'gte:min_limit'],
            'available_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string', 'exists:payment_methods,name'],
            'terms' => ['nullable', 'string', 'max:2000'],
        ]);

        // Escrow always settles from the maker's real-money wallet — the
        // currency pair above is a descriptive/matching label only, not a
        // selection of which of the platform's wallets to debit (the
        // create-offer form used to show a raw "Wallet Slug" text input for
        // this, which made no sense to a customer and had no relation to
        // what currency they actually wanted to trade).
        $validated['wallet_slug'] = 'qt_real_usd';
        $validated['currency'] = $validated['buy_currency'];
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

        if (! auth()->user()->getWallet($validated['wallet_slug'])) {
            return back()->with('error', 'Your real-money wallet could not be found. Contact support.')->withInput();
        }

        P2pOffer::create($validated);

        return redirect()->route('p2p-offers.index')->with('success', 'Offer published.');
    }

    public function edit(P2pOffer $p2pOffer): View
    {
        abort_unless($p2pOffer->user_id === auth()->id(), 403);

        return view('p2p.offers.edit', ['offer' => $p2pOffer]);
    }

    public function update(Request $request, P2pOffer $p2pOffer): RedirectResponse
    {
        abort_unless($p2pOffer->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'price_per_unit' => ['required', 'numeric', 'min:0.01'],
            'min_limit' => ['required', 'numeric', 'min:0.01'],
            'max_limit' => ['required', 'numeric', 'gte:min_limit'],
            'available_amount' => ['required', 'numeric', 'min:0.01'],
            'terms' => ['nullable', 'string', 'max:2000'],
        ]);

        $p2pOffer->update($validated);

        return redirect()->route('p2p-offers.index')->with('success', 'Offer updated.');
    }

    public function cancel(P2pOffer $p2pOffer): RedirectResponse
    {
        abort_unless($p2pOffer->user_id === auth()->id(), 403);

        if ($p2pOffer->trades()->whereNotIn('status', ['released', 'cancelled', 'resolved_buyer', 'resolved_seller'])->exists()) {
            return back()->with('error', 'This offer has open trades and cannot be cancelled.');
        }

        $p2pOffer->update(['status' => 'cancelled']);

        return redirect()->route('p2p-offers.index')->with('success', 'Offer cancelled.');
    }
}
