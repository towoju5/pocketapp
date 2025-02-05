<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use Illuminate\Http\Request;
use Necmicolak\YahooFinance\FinanceAsset;

class HomeController extends Controller
{
    public function dashboard(Request $request, $coin = null)
    {
        $isOutOfTradingHours = false;
        $data = Assets::where('symbol', "$coin")->first();
        if (!$data or $coin == null) {
            $data = Assets::first();
        }
        // check if asset is currently trading
        $asset = new FinanceAsset($data->yahoo_ticker);
        if ($asset) {
            $meta = $asset->getMeta();
            $currentTime = time();
            // Get the current trading periods
            $currentTradingPeriod = $meta['currentTradingPeriod'];
            // Check if the current time is within any of the market periods
            $isOutOfTradingHours = false;
            foreach (['pre', 'regular', 'post'] as $period) {
                $startTime = $currentTradingPeriod[$period]['start'];
                $endTime = $currentTradingPeriod[$period]['end'];

                // If current time is between the start and end time, the stock is trading
                if ($currentTime >= $startTime && $currentTime <= $endTime) {
                    $isOutOfTradingHours = true;
                    break;
                }
            }
        }

        $assetCategories = Assets::groupBy('asset_group')->get();

        return view('__dash', compact('data', 'assetCategories', 'isOutOfTradingHours'));
    }
}
