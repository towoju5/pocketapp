<?php

namespace App\Http\Controllers;

use App\Models\Signal;
use App\Models\Assets;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Events\NewTradeCreated;
use App\Events\TradeUpdated;
use App\Jobs\EvaluateTrade;

class SignalCopyController extends Controller
{
    public function copy(Request $request, $signalId)
    {
        $signal = Signal::findOrFail($signalId);

        $user = auth()->user();
        create_user_wallet($user->id);

        if (!debit_user($user->trade_wallet ?? 'qt_demo_usd', $signal->amount, "Copied Signal Trade")) {
            return response()->json(['errors' => "Insufficient wallet balance"], 402);
        }

        $asset = Assets::where('symbol', $signal->asset)->first();
        if (!$asset) {
            return response()->json(['errors' => "Asset not found"], 404);
        }

        $percentage_profit = $asset->asset_profit_margin;
        $profit_amount = ($percentage_profit / 100) * $signal->amount;
        $calculated_profit = $signal->amount + $profit_amount;

        $startPrice = $signal->start_price ?? getAssetData($asset->symbol, true); // Get live if not set

        try {
            $trade = Trade::create([
                "trade_currency" => $signal->asset,
                "trade_direction" => $signal->direction,
                "trade_amount" => $signal->amount,
                "trade_close_time" => now()->addSeconds($signal->duration),
                "trade_extra_info" => [
                    'copied_from_signal' => $signal->id,
                    'currentPrice' => $startPrice,
                ],
                "start_price" => $startPrice,
                "trade_status" => "pending",
                "trade_copied_count" => 0,
                'user_id' => $user->id,
                'trade_wallet' => $user->wallet_mode ?? 'qt_demo_usd',
                'trade_profit' => $calculated_profit,
                'trade_percentage' => $percentage_profit,
            ]);
        } catch (\Exception $e) {
            Log::error("Signal Copy Failed", ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Copying trade failed']);
        }

        event(new NewTradeCreated($trade));
        event(new TradeUpdated($trade));
        EvaluateTrade::dispatch($trade)->delay(now()->addSeconds($signal->duration));

        return response()->json([
            'status' => true,
            'message' => 'Signal copied and trade placed successfully!',
            'trade' => $trade,
        ]);
    }
}