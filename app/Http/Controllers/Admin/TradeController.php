<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use App\Services\TradeSettlementService;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = Trade::with('user')
            ->when($request->status, fn ($q) => $q->where('trade_status', $request->status))
            ->when($request->mode, fn ($q) => $q->where('trade_wallet', 'like', "%{$request->mode}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.trades.index', compact('trades'));
    }

    public function show(Trade $trade)
    {
        return view('admin.trades.show', compact('trade'));
    }

    /**
     * Immediately closes a pending trade as a win, crediting the payout
     * right away instead of waiting for its natural close time. The
     * originally-scheduled EvaluateTrade job still fires later at that
     * natural time, but is now a no-op against an already-settled trade
     * (see its guard at the top of handle()).
     */
    public function forceWin(Request $request, Trade $trade)
    {
        return $this->forceOutcome($request, $trade, 'win');
    }

    public function forceLose(Request $request, Trade $trade)
    {
        return $this->forceOutcome($request, $trade, 'lose');
    }

    private function forceOutcome(Request $request, Trade $trade, string $outcome)
    {
        if ($trade->trade_status !== 'pending' && $trade->trade_status !== 'open') {
            return back()->with('error', 'Only pending trades can be force-settled.');
        }

        $validated = $request->validate(['notes' => ['required', 'string', 'max:2000']]);

        $trade->admin_forced_outcome = $outcome;
        $trade->admin_action_by = auth()->id();
        $trade->admin_action_at = now();
        $trade->admin_action_notes = $validated['notes'];
        $trade->save();

        app(TradeSettlementService::class)->settle($trade, $outcome);

        return back()->with('success', "Trade #{$trade->id} forced to " . strtoupper($outcome) . '.');
    }

    /** Cancels a pending trade outright and refunds the stake — no win/lose settlement. */
    public function void(Request $request, Trade $trade)
    {
        if ($trade->trade_status !== 'pending' && $trade->trade_status !== 'open') {
            return back()->with('error', 'Only pending trades can be voided.');
        }

        $validated = $request->validate(['notes' => ['required', 'string', 'max:2000']]);

        $trade->admin_forced_outcome = 'void';
        $trade->admin_action_by = auth()->id();
        $trade->admin_action_at = now();
        $trade->admin_action_notes = $validated['notes'];
        $trade->save();

        app(TradeSettlementService::class)->void($trade);

        return back()->with('success', "Trade #{$trade->id} voided and stake refunded.");
    }
}
