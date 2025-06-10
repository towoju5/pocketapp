<?php

namespace App\Http\Controllers;

use App\Events\NewTradeCreated;
use App\Events\TradeUpdated;
use App\Models\Assets;
use App\Models\Trade;
use App\Jobs\EvaluateTrade;
use App\Models\Signal;
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
        if(!Schema::hasColumn('trades', 'trade_percentage')) {
            Schema::table('trades', function(Blueprint $table) {
                $table->string('trade_percentage')->default(0.9);
            });
        }
    }
    
    public function index(Request $request)
    {
        // return response()->json(Trade::all());
        $trades = Trade::whereUserId(auth()->id())->latest()->paginate(10);
        $signals = Signal::latest()->where('is_active', true)->get();
        $isExpress = false;
        return view('trades.index', compact('trades', 'signals', 'isExpress'));
    }

    public function placeTrade(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'asset' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'direction' => 'required|in:up,down',
            'order_token' => 'required|string',
            'order_time' => 'required',
            'duration' => 'required|string' // assuming HH:MM:SS
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $validated = $validated->validated();
        $user = auth()->user();

        // create_user_wallet($user->id);

        if(!debit_user($user->trade_wallet ?? 'qt_demo_usd', $validated['amount'], "Binary Trade Order")) {
            return response()->json(['errors' => "Insufficient wallet balance"], 402);
        }

        $symbol = str_replace('--', '/', $validated['asset']);
        $validated['asset'] = $symbol;

        $asset = Assets::where('symbol', $symbol)->first();
        if (!$asset) {
            return response()->json(['errors' => "Asset not found"], 404);
        }

        $timeParts = explode(':', $validated['duration']);
        $validated['duration'] = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];

        // return response()->json($validated);
        $currentPrice = base64_decode($request->order_token);
        Log::info('Current Price', ['price' => $currentPrice]);
        if (is_array($currentPrice) || null == $currentPrice || empty($currentPrice)) {
            return response()->json(['status' => false, 'message' => 'Error getting asset price']); exit;
        }

        $percentage_profit = $asset->asset_profit_margin;
        $profit_amount = ($percentage_profit / 100) * $validated['amount'];
        $calculated_profit = $validated['amount'] + $profit_amount;

        try {
            $trade = Trade::create([
                "trade_currency" => $symbol,
                "trade_direction" => $validated['direction'],
                "trade_amount" => $validated['amount'],
                "trade_close_time" => now()->addSeconds($validated['duration']),
                "trade_extra_info" => array_merge($validated, ['currentPrice' => $currentPrice]),
                "start_price" => $currentPrice,
                "trade_status" => "pending",
                "trade_copied_count" => 0,
                'user_id' => $user->id,
                'trade_wallet' => $user->wallet_mode ?? 'qt_demo_usd',
                'trade_profit' => $calculated_profit,
                'trade_percentage' => $percentage_profit,
            ]);
        } catch (\Exception $e) {
            Log::error("Trade creation failed", ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Trade creation failed']);
        }

        if (!$trade || !$trade->id) {
            return response()->json(['status' => false, 'message' => 'Error placing trade']);
        }

        event(new NewTradeCreated($trade));
        // event(new TradeUpdated($trade));
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
