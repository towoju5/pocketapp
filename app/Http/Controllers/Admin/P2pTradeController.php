<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\P2pTrade;
use Illuminate\Http\Request;

class P2pTradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = P2pTrade::with(['seller', 'buyer', 'offer'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.p2p-trades.index', compact('trades'));
    }

    public function show(P2pTrade $p2pTrade)
    {
        return view('admin.p2p-trades.show', ['trade' => $p2pTrade]);
    }

    public function resolve(Request $request, P2pTrade $p2pTrade)
    {
        if ($p2pTrade->status !== 'disputed') {
            return back()->with('error', 'Only disputed trades can be resolved.');
        }

        $validated = $request->validate([
            'decision' => ['required', 'in:buyer,seller'],
            'notes' => ['required', 'string', 'max:2000'],
        ]);

        $winner = $validated['decision'] === 'buyer' ? $p2pTrade->buyer : $p2pTrade->seller;
        $winner->getWallet($p2pTrade->wallet_slug)
            ->deposit($p2pTrade->amount, ['description' => "P2P dispute #{$p2pTrade->id} resolved in favor of {$validated['decision']}"]);

        $p2pTrade->update([
            'status' => $validated['decision'] === 'buyer' ? 'resolved_buyer' : 'resolved_seller',
            'resolved_by' => auth()->id(),
            'resolution_notes' => $validated['notes'],
            'resolved_at' => now(),
        ]);

        return redirect()->route('admin.p2p-trades.index')->with('success', 'Dispute resolved.');
    }
}
