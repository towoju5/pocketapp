<?php

namespace App\Http\Controllers;

use App\Events\NewTradeCreated;
use App\Models\Assets;
use App\Models\Trade;
use App\Jobs\EvaluateTrade;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Necmicolak\YahooFinance\FinanceAsset;

class TradeController extends Controller
{
    public function __construct()
    {
        //
    }
    
    public function index(Request $request)
    {
        $trades = Trade::whereUserId(auth()->id())->latest()->paginate(10);
        return view('trades.index', compact('trades'));
    }

    public function placeTrade(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'asset' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'direction' => 'required|in:up,down',
            'duration' => 'required|min:1',
        ]);

        $user = auth()->user();        
        create_user_wallet($user->id);
        if(!debit_user($user->trade_wallet ?? 'qt_demo_usd', $request->amount, "Binary Trade Order")) {
            $user->getWallet('qt_demo_usd')->deposit(1000000);
            return response()->json(['errors' => "Insufficient wallet balance"], 402);
        }

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $validated = $validated->validated();

        // $x = explode(":", $validated['asset']);
        // $asset = Assets::where('symbol', $x[1] ?? $x[0])->first();

        // Convert duration from HH:MM:SS to seconds
        $timeParts = explode(':', $validated['duration']);
        $validated['duration'] = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];


        $validated['asset'] = str_replace("/", "-", $validated['asset']);

        // Fetch initial market price
        $currentPrice = fetchPreChartData($validated['asset'], true);

        Log::info(json_encode($currentPrice));
        
        // Create the leader's trade
        $trade = Trade::create([
            "trade_currency" => $validated['asset'],
            "trade_direction" => $validated['direction'],
            "trade_amount" => $validated['amount'],
            "trade_close_time" => now()->addSeconds($validated['duration']),
            "trade_extra_info" => array_merge($validated, ['currentPrice' => $currentPrice]),
            "start_price" => $currentPrice,
            "trade_status" => "pending",
            "trade_copied_count" => 0,
            'user_id' => $user->id,
            'trade_wallet' => $user->wallet_mode ?? 'qt_demo_usd'
        ]);

        // Dispatch the NewTradeCreated event
        event(new NewTradeCreated($trade)); // This broadcasts the event

        // // Dispatch job for trade evaluation
        EvaluateTrade::dispatch($trade)->delay(now()->addSeconds($validated['duration']));

        return response()->json([
            'status' => true, 
            'message' => 'Trade placed successfully!', 
            'trade' => $trade,
            'html' => view("mini-pages.trade-list", compact('trade'))->render()
        ]);
    }

    private function getMarketPrice($market)
    {
        $asset = new FinanceAsset($market);
        if ($asset->getMeta() == null) {
            return response()->json(["error" => "Asset not found"]);
        }
        $finalPrice = $asset->getMeta()?->regularMarketPrice;

    }

    public function show($id)
    {
        $trade = Trade::findOrFail($id);
        return view('trades.show', compact('trade'));
    }

    public function store(Request $request)
    {
        return $this->placeTrade($request);
    }

    public function socialTrades()
    {
        return social_trades();
    }
}
