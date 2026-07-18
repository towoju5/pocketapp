<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionHistoryController extends Controller
{
    public function history(Request $request)
    {
        $current_user = auth()->user();

        // Get transactions as a query builder instance
        $transactions = $current_user->transactions()->newQuery();

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

        // Paginate results and return the view
        $transactions = $transactions->latest()->paginate(10)->appends($request->query());

        return view('finance.history', compact('transactions'));
    }
}
