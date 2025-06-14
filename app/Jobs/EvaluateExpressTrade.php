<?php

namespace App\Jobs;

use App\Models\ExpressTrade;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class EvaluateExpressTrade implements ShouldQueue
{
    use Queueable;

    public $trade;
    /**
     * Create a new job instance.
     */
    public function __construct($trade)
    {
        $this->trade = $trade;
    }

    /**
     * Execute the job.
     */
    
    public function handle()
    {
        try {
            $trade = $this->trade;

            Log::debug("Evaluating trade: " . $trade->id);
            $trade_currency = $trade->trade_currency;
            // Get the current price of the asset
            Log::info("Trade currency is: {$trade_currency}");
            $currentPrice = getAssetData($trade_currency, true);

            if (!is_numeric($currentPrice)) {
                Log::error("Invalid price received for asset {$trade_currency}: " . json_encode($currentPrice));
                $trade->trade_status = 'invalid';
                $trade->end_price = 0;
                $trade->save();
                return;
            }

            $finalPrice = $currentPrice;

            // Determine win or loss
            if ($trade->trade_direction === 'up' && $finalPrice > 0 && $finalPrice > $trade->start_price) {
                $trade->trade_status = 'win';
            } elseif ($trade->trade_direction === 'down' && $finalPrice > 0 && $finalPrice < $trade->start_price) {
                $trade->trade_status = 'win';
            } else {
                $trade->trade_status = 'lose';
            }

            $trade->end_price = $finalPrice;
            $trade->save();

            // check if this is the last trade in the express trade
            if ($trade->express_trade_id) {
                $lastTrade = ExpressTrade::where([
                    'trade_group_id' => $trade->trade_group_id,
                    'trade_status' => 'pending'
                ])->orderBy('id', 'desc')->first();
                if ($lastTrade && $lastTrade->id === $trade->id) {
                    // get all express trades with same trade group id and see if they are all complete and won
                    $all_trades = ExpressTrade::where(['trade_group_id' => $trade->trade_group_id])->get();
                    if ($all_trades->count() === $all_trades->where('trade_status', 'win')->count()) {
                        // all trades are won, credit user
                        $totalPayout = $all_trades->sum('trade_amount') + $all_trades->sum('trade_profit');
                        credit_user(
                            $trade->trade_wallet,
                            $totalPayout,
                            "Successfully won express trade ID {$trade->trade_group_id}"
                        );
                    }
                }
            }

            // Fire event for frontend or broadcast
            event(new \App\Events\ExpressTradeEvent($trade));
        } catch (\Throwable $th) {
            Log::error("Error finalizing trade ID : {$th->getMessage()}");
            Log::debug($th->getTraceAsString());
        }
    }
}
