<?php

namespace App\Jobs;

use App\Models\Tournament;
use App\Models\User;
use BinaryTrading\ExpressTrade\Models\Trade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Necmicolak\YahooFinance\FinanceAsset;

class EvaluateTrade implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $trade;

    public function __construct($trade)
    {
        $this->trade = $trade;
    }

    public function handle()
    {
        try {
            $trade = $this->trade;

            Log::debug("Evaluating trade: " . $trade->id);

            // Get the current price of the asset
            Log::info("Trade currency is: {$trade->trade_currency}");
            $currentPrice = getAssetData($trade->trade_currency, true);

            if (!is_numeric($currentPrice)) {
                Log::error("Invalid price received for asset {$trade->trade_currency}: " . json_encode($currentPrice));
                $trade->trade_status = 'invalid';
                $trade->end_price = 0;
                $trade->save();
                return;
            }

            $finalPrice = $currentPrice;

            // Determine win or loss
            if ($trade->trade_direction === 'up' && $finalPrice > $trade->start_price) {
                $trade->trade_status = 'win';
            } elseif ($trade->trade_direction === 'down' && $finalPrice < $trade->start_price) {
                $trade->trade_status = 'win';
            } else {
                $trade->trade_status = 'lose';
            }

            $trade->end_price = $finalPrice;
            $trade->save();

            // Credit user if won
            if ($trade->trade_status === 'win') {
                $totalPayout = $trade->trade_amount + $trade->trade_profit;
                credit_user(
                    $trade->trade_wallet,
                    $totalPayout,
                    "Successfully won trade ID {$trade->id}"
                );
            }

            // Fire event for frontend or broadcast
            event(new \App\Events\ExpressTradeEvent($trade));
        } catch (\Throwable $th) {
            Log::error("Error finalizing trade ID {$this->trade->id}: {$th->getMessage()}");
            Log::debug($th->getTraceAsString());
        }
    }

}
