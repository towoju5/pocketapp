<?php

namespace App\Http\Controllers;

use App\Events\NewTradeCreated;
use App\Models\Assets;
use App\Models\Trade;
use App\Jobs\EvaluateTrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Necmicolak\YahooFinance\FinanceAsset;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = Trade::latest()->cursorPaginate(10);
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

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }

        $validated = $validated->validated();

        $x = explode(":", $validated['asset']);
        $asset = Assets::where('symbol', $x[1] ?? $x[0])->first();


        $validated['asset'] = str_replace("/", "-", $asset->symbol);

        // Fetch initial market price
        $currentPrice = $this->getMarketPrice($asset->symbol);

        // Create the leader's trade
        $trade = Trade::create([
            "trade_currency" => $asset->symbol,
            "trade_direction" => $validated['direction'],
            "trade_amount" => $validated['amount'],
            "trade_close_time" => 10 ?? $validated['duration'],
            "trade_extra_info" => $validated,
            "trade_status" => "pending",
            "trade_copied_count" => 0,
            'user_id' => auth()->id(),
        ]);

        // Dispatch the NewTradeCreated event
        // event(new NewTradeCreated($trade)); // This broadcasts the event

        // // Dispatch job for trade evaluation
        // EvaluateTrade::dispatch($trade)->delay(now()->addSeconds($validated['duration']));

        return response()->json(['status' => true, 'message' => 'Trade placed successfully!', 'trade' => $trade]);
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
}
