<?php

namespace App\Http\Controllers;

use App\Models\Signal;
use App\Models\Assets;
use App\Models\Trade;
use App\Services\PriceFeedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Events\NewTradeCreated;
use App\Events\TradeUpdated;
use App\Jobs\EvaluateTrade;

class SignalCopyController extends Controller
{
    public function copy(Request $request, $signalId, PriceFeedService $priceFeed)
    {
        $signal = Signal::findOrFail($signalId);
        $user = auth()->user();

        $asset = Assets::where('symbol', $signal->asset)->first();
        if (!$asset) {
            return response()->json(['errors' => "Asset not found"], 404);
        }

        if (!$priceFeed->isOnline($signal->asset)) {
            return response()->json(['status' => false, 'message' => 'This asset is currently unavailable for trading.'], 422);
        }

        $currentPrice = $priceFeed->getPrice($signal->asset);
        if (null === $currentPrice) {
            return response()->json(['status' => false, 'message' => 'Unable to fetch the current price for this asset. Please try again.'], 422);
        }

        create_user_wallet($user->id);

        $walletSlug = $user->trade_wallet ?? 'qt_demo_usd';

        if (!debit_user($walletSlug, $signal->amount, "Copied Signal Trade")) {
            return response()->json(['errors' => "Insufficient wallet balance"], 402);
        }

        // asset_profit_margin is stored as a fraction (e.g. 0.92 == 92%), not a
        // 0-100 percentage — dividing by 100 here would shrink every payout
        // to roughly 1% of what it should be.
        $percentage_profit = $asset->asset_profit_margin;
        $profit_amount = $percentage_profit * $signal->amount;
        $calculated_profit = $signal->amount + $profit_amount;

        try {
            $trade = Trade::create([
                "trade_currency" => $signal->asset,
                "trade_direction" => $signal->direction,
                "trade_amount" => $signal->amount,
                "trade_close_time" => now()->addSeconds($signal->duration),
                "trade_extra_info" => [
                    'copied_from_signal' => $signal->id,
                    'currentPrice' => $currentPrice,
                ],
                "start_price" => $currentPrice,
                "trade_status" => "pending",
                "trade_copied_count" => 0,
                'user_id' => $user->id,
                'trade_wallet' => $walletSlug,
                'trade_profit' => $calculated_profit,
                'trade_percentage' => $percentage_profit,
            ]);
        } catch (\Exception $e) {
            Log::error("Signal Copy Failed", ['error' => $e->getMessage()]);
            credit_user($walletSlug, $signal->amount, "Refund: signal copy failed");
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