<?php

namespace App\Services;

use App\Models\ExpressTrade;

/**
 * Express-trade counterpart to TradeSettlementService — used by
 * ExpressTradeJob (the natural, price-driven close) and by
 * Admin\ExpressTradeController's force-win/force-lose/void actions, so an
 * admin override goes through the exact same wallet-crediting, cashback, and
 * referral-commission side effects a real settlement would.
 */
class ExpressTradeSettlementService
{
    /**
     * @param 'win'|'lose' $outcome
     */
    public function settle(ExpressTrade $trade, string $outcome, ?float $endPrice = null): void
    {
        if ($trade->trade_status !== 'open' && $trade->trade_status !== 'pending') {
            // Already settled (or voided) — never double-credit/double-count
            // referral commission for the same trade.
            return;
        }

        $trade->trade_status = $outcome;
        if ($endPrice !== null) {
            $trade->end_price = $endPrice;
        }
        $trade->save();

        if ($outcome === 'win') {
            $trade->user->getWallet($trade->trade_wallet)->deposit(
                $trade->trade_profit,
                ['description' => "Successfully won express trade ID {$trade->id}"]
            );
        } else {
            (new CashbackService())->applyLossCashback($trade->user, $trade);
        }

        (new ReferralCommissionService())->distribute(
            $trade->user,
            'express_trade',
            (float) $trade->trade_amount,
            $trade->trade_wallet,
            $trade
        );
    }

    /** Refunds the stake with no win/lose settlement — an admin cancelling a trade outright. */
    public function void(ExpressTrade $trade): void
    {
        if ($trade->trade_status !== 'open' && $trade->trade_status !== 'pending') {
            return;
        }

        $trade->trade_status = 'void';
        $trade->save();

        $trade->user->getWallet($trade->trade_wallet)->deposit(
            (float) $trade->trade_amount,
            ['description' => "Express trade ID {$trade->id} voided by admin — stake refunded"]
        );
    }
}
