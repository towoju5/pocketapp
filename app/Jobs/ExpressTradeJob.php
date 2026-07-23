<?php

namespace App\Jobs;

use App\Models\ExpressTrade;
use App\Services\ExpressTradeSettlementService;
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

    public function handle(PriceFeedService $priceFeed, ExpressTradeSettlementService $settlement): void
    {
        try {
            $trade = $this->trade->fresh();

            // Already settled — an admin's force-win/force-lose/void action
            // (see Admin\ExpressTradeController) can close a trade before its
            // natural expiry, and this same delayed job still fires at the
            // original close time regardless.
            if (!$trade || $trade->trade_status !== 'open') {
                return;
            }

            if ($trade->admin_forced_outcome) {
                $settlement->settle($trade, $trade->admin_forced_outcome);
                return;
            }

            $finalPrice = $priceFeed->getPrice($trade->trade_currency) ?? getAssetData($trade->trade_currency, true);
            $finalPrice = is_numeric($finalPrice) ? (float) $finalPrice : 0;

            if ($trade->trade_direction === 'up' && $finalPrice > 0 && $finalPrice > $trade->start_price) {
                $outcome = 'win';
            } elseif ($trade->trade_direction === 'down' && $finalPrice > 0 && $finalPrice < $trade->start_price) {
                $outcome = 'win';
            } else {
                $outcome = 'lose';
            }

            $settlement->settle($trade, $outcome, $finalPrice);
        } catch (\Throwable $e) {
            Log::error('ExpressTradeJob failed', ['trade_id' => $this->trade->id, 'error' => $e->getMessage()]);
        }
    }
}
