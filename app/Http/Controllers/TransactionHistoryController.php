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
        if ($request->filled('date-range')) {
            $dates = explode('-', $request->input('date-range'));

            if (count($dates) === 2) {
                try {
                    $dateFrom = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                    $dateTo = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();

                    $transactions->whereBetween('created_at', [$dateFrom, $dateTo]);
                } catch (\Exception $e) {
                    return back()->withErrors(['date-range' => 'Invalid date range format.']);
                }
            } else {
                return back()->withErrors(['date-range' => 'Invalid date range input.']);
            }
        }

        // Filter by transaction type if provided
        if ($request->filled('type')) {
            $transactions->where('type', $request->type);
        }

        // Paginate results and return the view
        return $transactions = $transactions->latest()->paginate(10)->appends($request->query());
        return view('finance.history', compact('transactions'));
    }
}
