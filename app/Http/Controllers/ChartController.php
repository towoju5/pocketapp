<?php

namespace App\Http\Controllers;

use App\Services\CashbackService;
use Illuminate\Http\Request;
use Necmicolak\YahooFinance\FinanceAsset;

class ChartController extends Controller
{
    public function stream(Request $request, $symbol)
    {
        $asset = $asset = new FinanceAsset($symbol);
        if ($asset->getChart() == null) {
            return response()->json(["error" => "Asset not found"]);
        }
        return response()->json($asset->getChart());
    }

    public function cashback(CashbackService $cashbackService)
    {
        $user = auth()->user();
        $rule = $cashbackService->activeLossRule();

        $cashbackQuery = $user->transactions()->where('meta', 'like', '%Loss cashback%');

        $totalCashback = (clone $cashbackQuery)->sum('amount');
        $lastPayout = (clone $cashbackQuery)->latest()->first();
        $payouts = $cashbackQuery->latest()->paginate(10);

        return view('finance.cashback', [
            'rule' => $rule,
            'payouts' => $payouts,
            'totalCashback' => $totalCashback,
            'lastPayout' => $lastPayout,
        ]);
    }

}
