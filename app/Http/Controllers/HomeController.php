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
        $isOutOfTradingHours = true;
        if(isset($coin)) {
            $coin = str_replace('--', '/', $coin);
        }

        $data = Assets::where('symbol', $coin)->first();

        if (!$data or $coin == null) {
            $data = Assets::first();
        }
        
        $current_rate = getAssetData($coin, true);
        if(is_numeric($current_rate)) {
            $isOutOfTradingHours = false;
        }


        $assetCategories = Assets::groupBy('asset_group')->get();
        $chart_coin = $data->symbol;
        $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->get();

        return view('__dash', compact('data', 'assetCategories', 'isOutOfTradingHours', 'active_trades', 'chart_coin'));
    }

    public function demo(Request $request, $coin = null)
    {
        $$user = auth()->user();
        $isOutOfTradingHours = false;
        if(isset($coin)) {
            $coin = str_replace('--', '/', $coin);
        }

        $data = Assets::where('symbol', $coin)->first();

        if (!$data or $coin == null) {
            $data = Assets::first();
        }

        $assetCategories = Assets::groupBy('asset_group')->get();
        $chart_coin = $data->symbol;
        $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->get();

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
