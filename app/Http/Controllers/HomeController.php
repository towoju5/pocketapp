<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\ExpressTrade;
use App\Models\Signal;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(Request $request, $coin = null)
    {
        $user = auth()->user();

        if($request->routeIs('dashboard.demo')) {
            $user->active_wallet_slug = 'qt_demo_usd';
            $user->save();
        } else {
            $user->active_wallet_slug = 'qt_real_usd';
            $user->save();
        }

        $isOutOfTradingHours = false;
        if (isset($coin)) {
            $coin = str_replace('--', '/', $coin);
        }

        $data = Assets::where('symbol', $coin)->first();

        if (!$data or $coin == null) {
            $data = Assets::first();
        }

        $current_rate = getAssetData($coin, true);
        if(!is_numeric($current_rate)) {
            // var_dump($current_rate); exit;
            $isOutOfTradingHours = true;
        }

        // Get assets
        $assets = Assets::where(['is_otc' => true, 'is_active' => true])->get();
        $assetCategories = Assets::groupBy('asset_group')->get();
        $chart_coin = $data->symbol;
        $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->latest()->get();
        $recent_closed_trades = Trade::whereIn("trade_status", ["pending", "win", "lose"])->whereUserId(auth()->id())->whereBetween('created_at', [now()->subMinutes(10), now()])->latest()->limit(6)->get();
        $signals = Signal::latest()->where('is_active', true)->get();
        $traders24hours = Trade::where('trade_status', 'win')->with('user')->orderBy('trade_profit', 'desc')->where('created_at', '>=', now()->subHours(24))->get();
        $tradersTopRanked = Trade::where('trade_status', 'win')->with('user')->orderBy('trade_profit', 'desc')->get();
        $tradersTop100 = Trade::where('trade_status', 'win')->with('user')->orderBy('trade_profit', 'desc')->limit(100)->get();
        // Users with trades in the past 24 hours
        $traders24hours = User::whereHas('trades', function ($q) {
            $q->where('created_at', '>=', now()->subHours(24))
                ->where('trade_status', 'win');
        })
            ->with(['trades' => function ($q) {
                $q->where('created_at', '>=', now()->subHours(24));
            }])
            ->get()
            ->map(function ($user) {
                $user->total_profit = $user->trades->where('trade_status', 'win')->sum('trade_profit');
                return $user;
            })
            ->sortByDesc('total_profit')
            ->values();

        // All-time top ranked users
        $tradersTopRanked = User::whereHas('trades', function ($q) {
            $q->where('trade_status', 'win');
        })
            ->with('trades')
            ->get()
            ->map(function ($user) {
                $user->total_profit = $user->trades->where('trade_status', 'win')->sum('trade_profit');
                return $user;
            })
            ->sortByDesc('total_profit')
            ->values();

        // Top 100 users by total profit (all time)
        $tradersTop100 = $tradersTopRanked->take(100);

        $openedExpressTrades = ExpressTrade::where('user_id', $user->id)->get();

        // return [
        //     $traders24hours,
        //     $tradersTopRanked,
        //     $tradersTop100,
        // ];

        return view('__dash', compact([
            'data',
            'assetCategories',
            'isOutOfTradingHours',
            'active_trades',
            'chart_coin',
            'signals',
            'traders24hours',
            'tradersTopRanked',
            'tradersTop100',
            'assets',
            'recent_closed_trades',
            'openedExpressTrades'
        ]));
    }

    // public function demo(Request $request, $coin = null)
    // {
    //     $user = auth()->user();
    //     $user->active_wallet_slug = 'qt_demo_usd';
    //     $user->save();

    //     $isOutOfTradingHours = false;
    //     if (isset($coin)) {
    //         $coin = str_replace('--', '/', $coin);
    //     }

    //     $data = Assets::where('symbol', $coin)->first();

    //     if (!$data or $coin == null) {
    //         $data = Assets::first();
    //     }

    //     $assetCategories = Assets::groupBy('asset_group')->get();
    //     $chart_coin = $data->symbol;
    //     $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->get();

    //     $wallet_balance = $user->getWallet($user->active_wallet_slug) ?? ["balance" => 0];

    //     return view('__dash', compact('data', 'assetCategories', 'isOutOfTradingHours', 'active_trades', 'chart_coin', 'wallet_balance'));
    // }

    public function get_asset_history($ticker, $isOTC = true)
    {
        $symbol = $ticker . "_Strike";
        $history = file_get_contents("https://iqcent.com/trade-api/history?from=1745684639&to=1745702639&symbol={$symbol}&firstDataRequest=true&resolution=1");
        return $history;
    }

    public function getTicks()
    {
        // $scraper = new IqcentDomScraper();
        // $symbol = 'EUR/USD.X';
        // $from = $to = now()->timestamp * 1000;

        // $data = $scraper->getTickData($symbol, $from, $to);

        // return response()->json(json_decode($data, true));
    }
}
