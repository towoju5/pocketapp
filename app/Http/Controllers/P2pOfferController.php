<?php

namespace App\Http\Controllers;

use App\Models\P2pOffer;
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
            ->when($request->currency, fn ($q) => $q->where('currency', $request->currency))
            ->latest()
            ->paginate(15);

        $myOffers = P2pOffer::where('user_id', auth()->id())->latest()->get();

        return view('p2p.offers.index', compact('offers', 'myOffers'));
    }

    public function create(): View
    {
        return view('p2p.offers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:buy,sell'],
            'wallet_slug' => ['required', 'string'],
            'currency' => ['required', 'string', 'max:10'],
            'price_per_unit' => ['required', 'numeric', 'min:0.01'],
            'min_limit' => ['required', 'numeric', 'min:0.01'],
            'max_limit' => ['required', 'numeric', 'gte:min_limit'],
            'available_amount' => ['required', 'numeric', 'min:0.01'],
            'payment_methods' => ['nullable', 'array'],
            'terms' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! auth()->user()->getWallet($validated['wallet_slug'])) {
            return back()->withErrors(['wallet_slug' => 'Invalid wallet.'])->withInput();
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'active';

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
