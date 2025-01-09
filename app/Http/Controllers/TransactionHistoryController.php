<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionHistoryController extends Controller
{
    public function history(Request $request)
    {
        $current_user = auth()->user();

    // Get the transactions associated with the current user
    $transactions = $current_user->transactions();

    // Check if 'date-range' is provided
    if ($request->has('date-range')) {
        $dateRange = $request->input('date-range');
        $dates = explode('-', $dateRange);

        // Validate and parse the dates
        if (count($dates) === 2) {
            try {
                $dateFrom = Carbon::parse(trim($dates[0]))->startOfDay();
                $dateTo = Carbon::parse(trim($dates[1]))->endOfDay();

                // Filter transactions within the date range
                $transactions = $transactions->whereBetween('created_at', [$dateFrom, $dateTo]);
            } catch (\Exception $e) {
                // Handle invalid date format
                return back()->withErrors(['date-range' => 'Invalid date range format.']);
            }
        } else {
            return back()->withErrors(['date-range' => 'Invalid date range input.']);
        }
    }

    // Paginate the transactions, sorted by the latest
    $transactions = $transactions->latest()->paginate(10);

    // Return the view with transactions
    return view('finance.history', compact('transactions'));
    }
}
