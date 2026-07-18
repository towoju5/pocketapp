<?php

namespace App\Http\Controllers;

use App\Events\NewTradeCreated;
use App\Events\TradeUpdated;
use App\Models\Assets;
use App\Models\Trade;
use App\Jobs\EvaluateTrade;
use App\Models\User;
use App\Services\PriceFeedService;
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
        $currentMode = is_demo_wallet(auth()->user()->trade_wallet ?? 'qt_demo_usd') ? 'demo' : 'real';
        $mode = in_array($request->input('mode'), ['demo', 'real']) ? $request->input('mode') : $currentMode;

        $query = Trade::whereUserId(auth()->id())->where('trade_wallet', 'like', "%{$mode}%");

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date('date_to'));
        }
        if ($request->filled('asset')) {
            $query->where('trade_currency', $request->input('asset'));
        }
        if ($request->filled('result') && $request->input('result') !== 'all') {
            $query->where('trade_status', $request->input('result'));
        }

        $trades = $query->latest()->paginate(20)->withQueryString();
        $assets = Trade::whereUserId(auth()->id())->where('trade_wallet', 'like', "%{$mode}%")->select('trade_currency')->distinct()->orderBy('trade_currency')->pluck('trade_currency');

        return view('trades.index', compact('trades', 'assets', 'mode'));
    }

    public function placeTrade(Request $request, PriceFeedService $priceFeed)
    {
        $validated = Validator::make($request->all(), [
            'asset' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'direction' => 'required|in:up,down',
            'duration' => 'required|string' // assuming HH:MM:SS
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $validated = $validated->validated();
        $user = auth()->user();

        $symbol = str_replace('--', '/', $validated['asset']);
        $validated['asset'] = $symbol;

        $asset = Assets::where('symbol', $symbol)->first();
        if (!$asset) {
            return response()->json(['errors' => "Asset not found"], 404);
        }

        if (!$priceFeed->isOnline($symbol)) {
            return response()->json(['status' => false, 'message' => 'This asset is currently unavailable for trading.'], 422);
        }

        $currentPrice = $priceFeed->getPrice($symbol);
        if (null === $currentPrice) {
            return response()->json(['status' => false, 'message' => 'Unable to fetch the current price for this asset. Please try again.'], 422);
        }

        $timeParts = explode(':', $validated['duration']);
        $validated['duration'] = ($timeParts[0] * 3600) + ($timeParts[1] * 60) + $timeParts[2];

        create_user_wallet($user->id);

        $walletSlug = $user->trade_wallet ?? 'qt_demo_usd';

        if(!debit_user($walletSlug, $validated['amount'], "Binary Trade Order")) {
            return response()->json(['errors' => "Insufficient wallet balance"], 402);
        }

        // asset_profit_margin is stored as a fraction (e.g. 0.92 == 92%), not a
        // 0-100 percentage — dividing by 100 here would shrink every payout
        // to roughly 1% of what it should be.
        $percentage_profit = $asset->asset_profit_margin;
        $profit_amount = $percentage_profit * $validated['amount'];
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
                'trade_wallet' => $walletSlug,
                'trade_profit' => $calculated_profit,
                'trade_percentage' => $percentage_profit,
            ]);
        } catch (\Exception $e) {
            Log::error("Trade creation failed", ['error' => $e->getMessage()]);
            credit_user($walletSlug, $validated['amount'], "Refund: trade creation failed");
            return response()->json(['status' => false, 'message' => 'Trade creation failed']);
        }

        if (!$trade || !$trade->id) {
            return response()->json(['status' => false, 'message' => 'Error placing trade']);
        }

        event(new NewTradeCreated($trade));
        event(new TradeUpdated($trade));
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
