<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionHistoryController extends Controller
{
    public function history(Request $request)
    {
        $current_user = auth()->user();

        $mode = in_array($request->input('mode'), ['demo', 'real'])
            ? $request->input('mode')
            : (is_demo_wallet($current_user->trade_wallet ?? 'qt_demo_usd') ? 'demo' : 'real');

        // Get transactions as a query builder instance, scoped to the wallet
        // mode currently in view — demo and real activity must never mix.
        $transactions = $current_user->transactions()->newQuery()
            ->whereHas('wallet', function ($q) use ($mode) {
                $q->where('slug', 'like', "%{$mode}%");
            });

        // Handle date range filtering
        if ($request->filled('date_from')) {
            try {
                $transactions->where('created_at', '>=', Carbon::parse($request->input('date_from'))->startOfDay());
            } catch (\Exception $e) {
                return back()->withErrors(['date_from' => 'Invalid date.']);
            }
        }
        if ($request->filled('date_to')) {
            try {
                $transactions->where('created_at', '<=', Carbon::parse($request->input('date_to'))->endOfDay());
            } catch (\Exception $e) {
                return back()->withErrors(['date_to' => 'Invalid date.']);
            }
        }

        // Filter by transaction type if provided
        if ($request->filled('type')) {
            $transactions->where('type', $request->type);
        }

        // Live search — matches the transaction's UUID, type, or amount.
        if ($request->filled('q')) {
            $needle = trim($request->input('q'));
            $transactions->where(function ($q) use ($needle) {
                $q->where('uuid', 'like', "%{$needle}%")
                    ->orWhere('type', 'like', "%{$needle}%")
                    ->orWhere('amount', 'like', "%{$needle}%");
            });
        }

        // Paginate results and return the view
        $transactions = $transactions->latest()->paginate(10)->appends($request->query());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'rows' => view('finance.partials.history-rows', compact('transactions'))->render(),
                'pagination' => $transactions->links('pagination::tailwind')->toHtml(),
            ]);
        }

        return view('finance.history', compact('transactions', 'mode'));
    }
}
