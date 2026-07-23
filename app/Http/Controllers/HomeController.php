<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\ExpressTrade;
use App\Models\Signal;
use App\Models\Trade;
use App\Models\User;
use App\Services\PriceFeedService;
use App\Services\TraderLeaderboard;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(Request $request, PriceFeedService $priceFeed, $coin = null)
    {
        $user = auth()->user();
        if (isset($coin)) {
            $coin = str_replace('--', '/', $coin);
        }

        $data = Assets::where('symbol', $coin)->first();

        if (!$data or $coin == null) {
            $data = Assets::first();
        }

        $isOutOfTradingHours = !$priceFeed->isOnline($data->symbol);

        $assetCategories = Assets::groupBy('asset_group')->get();
        $chart_coin = $data->symbol;
        // Dashboard scopes to whichever wallet (demo/real) the user currently has
        // active — demo and real trades must never be shown mixed together.
        $walletMode = is_demo_wallet($user->trade_wallet ?? 'qt_demo_usd') ? 'demo' : 'real';
        $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->where('trade_wallet', 'like', "%{$walletMode}%")->latest()->get();
        $recent_closed_trades = Trade::whereIn("trade_status", ["pending", "win", "lose"])->whereUserId(auth()->id())->where('trade_wallet', 'like', "%{$walletMode}%")->whereBetween('created_at', [now()->subMinutes(10), now()])->latest()->get();
        $signals = Signal::latest()->where('is_active', true)->get();
        ['traders24hours' => $traders24hours, 'tradersTopRanked' => $tradersTopRanked, 'tradersTop100' => $tradersTop100] = TraderLeaderboard::build();

        // Express trading only ever lists assets currently streaming — a
        // symbol iqcent has open but this app isn't receiving live ticks for
        // right now would just place trades against a stale/absent price.
        $assets = Assets::where('is_otc', true)->get()->filter(fn ($asset) => $priceFeed->isOnline($asset->symbol))->values();
        $openedExpressTrades = ExpressTrade::where('user_id', $user->id)->where('trade_status', 'open')->where('trade_wallet', 'like', "%{$walletMode}%")->with('asset')->latest()->get();
        $closedExpressTrades = ExpressTrade::where('user_id', $user->id)->whereIn('trade_status', ['win', 'lose'])->where('trade_wallet', 'like', "%{$walletMode}%")->with('asset')->latest()->take(20)->get();

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
            'openedExpressTrades',
            'closedExpressTrades'
        ]));
    }

    public function demo(Request $request, PriceFeedService $priceFeed, $coin = null)
    {
        $user = auth()->user();
        if (isset($coin)) {
            $coin = str_replace('--', '/', $coin);
        }

        $data = Assets::where('symbol', $coin)->first();

        if (!$data or $coin == null) {
            $data = Assets::first();
        }

        $isOutOfTradingHours = !$priceFeed->isOnline($data->symbol);

        // /dashboard/demo always trades on the demo wallet, regardless of
        // whatever the user last had active elsewhere — visiting this URL is
        // an explicit switch to practice mode so trades placed here can never
        // land on the real wallet.
        if (!is_demo_wallet($user->trade_wallet)) {
            $user->trade_wallet = 'qt_demo_usd';
            $user->active_wallet_slug = 'qt_demo_usd';
            $user->save();
        }

        $assetCategories = Assets::groupBy('asset_group')->get();
        $chart_coin = $data->symbol;
        $active_trades = Trade::where(["trade_status" => "pending", "user_id" => auth()->id()])->where('trade_wallet', 'like', '%demo%')->latest()->get();
        $recent_closed_trades = Trade::whereIn("trade_status", ["pending", "win", "lose"])->whereUserId(auth()->id())->where('trade_wallet', 'like', '%demo%')->whereBetween('created_at', [now()->subMinutes(10), now()])->latest()->get();
        $signals = Signal::latest()->where('is_active', true)->get();
        ['traders24hours' => $traders24hours, 'tradersTopRanked' => $tradersTopRanked, 'tradersTop100' => $tradersTop100] = TraderLeaderboard::build();

        $assets = Assets::where('is_otc', true)->get()->filter(fn ($asset) => $priceFeed->isOnline($asset->symbol))->values();
        $openedExpressTrades = ExpressTrade::where('user_id', $user->id)->where('trade_status', 'open')->where('trade_wallet', 'like', '%demo%')->with('asset')->latest()->get();
        $closedExpressTrades = ExpressTrade::where('user_id', $user->id)->whereIn('trade_status', ['win', 'lose'])->where('trade_wallet', 'like', '%demo%')->with('asset')->latest()->take(20)->get();

        $wallet_balance = $user->getWallet($user->active_wallet_slug ?? 'qt_demo_usd') ?? ["balance" => 0];

        return view('__dash', compact([
            'data',
            'assetCategories',
            'isOutOfTradingHours',
            'active_trades',
            'chart_coin',
            'wallet_balance',
            'signals',
            'traders24hours',
            'tradersTopRanked',
            'tradersTop100',
            'assets',
            'recent_closed_trades',
            'openedExpressTrades',
            'closedExpressTrades'
        ]));
    }

    public function assetStatus(PriceFeedService $priceFeed)
    {
        $status = Assets::pluck('symbol')->mapWithKeys(fn ($symbol) => [$symbol => $priceFeed->isOnline($symbol)]);

        return response()->json($status);
    }


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
