<?php

namespace App\Jobs;

use App\Models\ExpressTrade;
use App\Services\PriceFeedService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ExpressTradeJob implements ShouldQueue
{
    use Queueable;

    public function __construct(protected ExpressTrade $trade)
    {
    }

    public function handle(PriceFeedService $priceFeed): void
    {
        try {
            $trade = $this->trade->fresh();
            if (!$trade) {
                return;
            }

            $finalPrice = $priceFeed->getPrice($trade->trade_currency) ?? getAssetData($trade->trade_currency, true);
            $finalPrice = is_numeric($finalPrice) ? (float) $finalPrice : 0;

            if ($trade->trade_direction === 'up' && $finalPrice > 0 && $finalPrice > $trade->start_price) {
                $trade->trade_status = 'win';
            } elseif ($trade->trade_direction === 'down' && $finalPrice > 0 && $finalPrice < $trade->start_price) {
                $trade->trade_status = 'win';
            } else {
                $trade->trade_status = 'lose';
            }

            $trade->end_price = $finalPrice;
            $trade->save();

            if ($trade->trade_status === 'win') {
                // trade_profit already IS the total payout (stake + profit) —
                // adding trade_amount again would double-refund the stake.
                $trade->user->getWallet($trade->trade_wallet)->deposit(
                    $trade->trade_profit,
                    ['description' => "Successfully won express trade ID {$trade->id}"]
                );
            }

            (new \App\Services\ReferralCommissionService())->distribute(
                $trade->user,
                'express_trade',
                (float) $trade->trade_amount,
                $trade->trade_wallet,
                $trade
            );
        } catch (\Throwable $e) {
            Log::error('ExpressTradeJob failed', ['trade_id' => $this->trade->id, 'error' => $e->getMessage()]);
        }
    }
}
