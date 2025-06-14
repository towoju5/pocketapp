<?php

namespace App\Http\Controllers;

use App\Events\ExpressTradeEvent;
use App\Events\NewExpressTradeCreated;
use App\Jobs\EvaluateExpressTrade;
use App\Jobs\EvaluateTrade;
use App\Models\Assets;
use App\Models\ExpressTrade;
use App\Models\Signal;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpressTradeController extends Controller
{
    public function index()
    {
        $trades = ExpressTrade::with('asset')->whereUserId(auth()->id())->latest()->paginate(10);
        $signals = Signal::latest()->where('is_active', true)->get();
        $isExpress = true;
        return view('trades.index', compact('trades', 'signals', 'isExpress'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset' => 'required|string',
            'direction' => 'required|in:up,down',
            'duration' => 'required|string' // assuming HH:ii:ss
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = auth()->user();
        $data = $validator->validated();
        $data['user_id'] = $user->id;
        if ($express = ExpressTrade::create($data)) {
            broadcast(new ExpressTradeEvent($express));
            return response()->json([
                'status' => true,
                'message' => 'Trade placed successfully!',
                'trade' => $express,
                'html' => view("mini-pages.express-trade", compact(['trade' => $express]))->render()
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Error placing trade']);
    }

    public function show($id)
    {
        $trade = ExpressTrade::findOrFail($id);
        return view('trades.show', compact('trade'));
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'trades' => 'required|array|min:1',
            'trades.*.asset_id' => 'required|integer|exists:assets,id',
            'trades.*.asset' => 'required|string',
            'trades.*.direction' => 'required|in:up,down',
            'trades.*.close_time'  => 'required|integer|min:1',
            'trades.*.percentage' => 'required|numeric|min:1|max:100',
        ]);

        if(!debit_user($user->trade_wallet ?? 'qt_demo_usd', $validated['amount'], "Express Trade Order")) {
            return response()->json(['errors' => "Insufficient wallet balance"], 402);
        }
        
        $user = auth()->user();
        $groupId = generate_uuid(); // For grouping all trades
        $trades = [];
        foreach ($validated['trades'] as $trade) {
            $asset = Assets::find($trade['asset_id']);
            if (!$asset || !isset($asset->symbol)) {
                return response()->json(['error' => "Asset not found for ID {$trade['asset_id']}"], 404);
            }

            $durationInSeconds = $trade['close_time'];
            $percentage_profit = $asset->asset_profit_margin;
            $profit_amount = ($percentage_profit / 100) * $validated['amount'];
            $calculated_profit = $validated['amount'] + $profit_amount;
            $current_price = getAssetData($asset->symbol, true);
            $closeTime = now()->addSeconds($durationInSeconds);

            $trade = ExpressTrade::create([
                "user_id" => $user->id,
                "asset_id" => $trade['asset_id'],
                "trade_group_id" => $groupId,
                "trade_direction" => $trade['direction'],
                "trade_amount" => $validated['amount'],
                "trade_close_time" => $closeTime,
                "trade_currency" => $asset->symbol,
                "start_price" => $current_price,
                'trade_wallet' => $user->wallet_mode ?? 'qt_demo_usd',
                'trade_profit' => $calculated_profit,
                "trade_extra_info" => $trade,
                "trade_status" => 'pending',
                "trade_percentage" => floatval($trade['percentage'] / 100),
            ]);

            $trades[] = $trade;

            EvaluateExpressTrade::dispatch($trade)->delay(now()->addSeconds($closeTime));
            event(new NewExpressTradeCreated($trade));
        }

        return response()->json([
            'status' => true, 
            'message' => 'Trade placed successfully!', 
            'trade' => $trade,
            'html' => view("mini-pages.express-trade", compact('trades'))->render()
        ]);

        return response()->json(['message' => "Trades successfully created"], 200);
    }
}
