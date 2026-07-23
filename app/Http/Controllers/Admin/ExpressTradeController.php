<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpressTrade;
use App\Services\ExpressTradeSettlementService;
use Illuminate\Http\Request;

class ExpressTradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = ExpressTrade::with(['user', 'asset'])
            ->when($request->status, fn ($q) => $q->where('trade_status', $request->status))
            ->when($request->mode, fn ($q) => $q->where('trade_wallet', 'like', "%{$request->mode}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.express-trades.index', compact('trades'));
    }

    public function show(ExpressTrade $expressTrade)
    {
        return view('admin.express-trades.show', ['trade' => $expressTrade]);
    }

    public function forceWin(Request $request, ExpressTrade $expressTrade)
    {
        return $this->forceOutcome($request, $expressTrade, 'win');
    }

    public function forceLose(Request $request, ExpressTrade $expressTrade)
    {
        return $this->forceOutcome($request, $expressTrade, 'lose');
    }

    private function forceOutcome(Request $request, ExpressTrade $trade, string $outcome)
    {
        if ($trade->trade_status !== 'open') {
            return back()->with('error', 'Only open trades can be force-settled.');
        }

        $validated = $request->validate(['notes' => ['required', 'string', 'max:2000']]);

        $trade->admin_forced_outcome = $outcome;
        $trade->admin_action_by = auth()->id();
        $trade->admin_action_at = now();
        $trade->admin_action_notes = $validated['notes'];
        $trade->save();

        app(ExpressTradeSettlementService::class)->settle($trade, $outcome);

        return back()->with('success', "Express trade #{$trade->id} forced to " . strtoupper($outcome) . '.');
    }

    /** Cancels an open trade outright and refunds the stake — no win/lose settlement. */
    public function void(Request $request, ExpressTrade $expressTrade)
    {
        if ($expressTrade->trade_status !== 'open') {
            return back()->with('error', 'Only open trades can be voided.');
        }

        $validated = $request->validate(['notes' => ['required', 'string', 'max:2000']]);

        $expressTrade->admin_forced_outcome = 'void';
        $expressTrade->admin_action_by = auth()->id();
        $expressTrade->admin_action_at = now();
        $expressTrade->admin_action_notes = $validated['notes'];
        $expressTrade->save();

        app(ExpressTradeSettlementService::class)->void($expressTrade);

        return back()->with('success', "Express trade #{$expressTrade->id} voided and stake refunded.");
    }
}
