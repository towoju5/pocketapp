<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\Trade;
use Illuminate\Http\Request;
use Necmicolak\YahooFinance\FinanceAsset;

class HomeController extends Controller
{
    public function dashboard(Request $request, $coin = null)
    {
        $user = auth()->user();
        $isOutOfTradingHours = false;
        if(isset($coin)) {
            $coin = str_replace('--', '/', $coin);
            // var_dump($coin); exit;
        }

        $data = Assets::where('symbol', $coin)->first();

        if (!$data or $coin == null) {
            $data = Assets::first();
        }
        
        $assetCategories = Assets::groupBy('asset_group')->get();
        $chart_coin = $data->symbol;
        $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->get();

        return view('__dash', compact('data', 'assetCategories', 'isOutOfTradingHours', 'active_trades', 'chart_coin'));
    }

    public function demo(Request $request, $coin = null)
    {
        $user = auth()->user();
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
        $wallet_balance = $user->getWallet($user->active_wallet_slug ?? 'qt_demo_usd') ?? ["balance" => 0];

        return view('__dash', compact('data', 'assetCategories', 'isOutOfTradingHours', 'wallet_balance'));
    }

    public function get_asset_history($ticker, $isOTC = true)
    {
        $symbol = $ticker."_Strike";
        $history = file_get_contents("https://iqcent.com/trade-api/history?from=1745684639&to=1745702639&symbol={$symbol}&firstDataRequest=true&resolution=1");
        return $history;
    }
}
