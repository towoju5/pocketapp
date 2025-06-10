<?php

namespace App\Http\Controllers;

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

    public function cashback()
    {
        return view('finance.cashback');
    }

}
