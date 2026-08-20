<?php

namespace App\Services;

use App\Models\Trade;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * The single place a pending trade actually settles — used by EvaluateTrade
 * (the natural, price-driven close at expiry) and by Admin\TradeController's
 * force-win/force-lose actions, so an admin override goes through the exact
 * same wallet-crediting, cashback, and referral-commission side effects a
 * real settlement would, instead of a parallel path that could drift out of
 * sync with it.
 */
class TradeSettlementService
{
    /**
     * @param 'win'|'lose' $outcome
     */
    public function settle(Trade $trade, string $outcome, ?float $endPrice = null): void
    {
        if ($trade->trade_status !== 'pending' && $trade->trade_status !== 'open') {
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
            // trade_profit already IS the total payout (stake + profit,
            // computed as such in TradeController::placeTrade()) — adding
            // trade_amount again here would double-refund the stake.
            $trade->user->getWallet($trade->trade_wallet)->deposit(
                $trade->trade_profit,
                ['description' => "Successfully won trade ID {$trade->id}"]
            );
        } else {
            (new CashbackService())->applyLossCashback($trade->user, $trade);
        }

        // Referral commission is volume-based (fires on every closed trade,
        // win or lose) rather than profit-based, to keep the rule simple and
        // avoid incentive-gaming complexity.
        (new ReferralCommissionService())->distribute(
            $trade->user,
            'trade',
            (float) $trade->trade_amount,
            $trade->trade_wallet,
            $trade
        );

        event(new \App\Events\TradeUpdated($trade));
        $this->pushToTradeSocket($trade);
    }

    /** Refunds the stake with no win/lose settlement — an admin cancelling a trade outright. */
    public function void(Trade $trade): void
    {
        if ($trade->trade_status !== 'pending' && $trade->trade_status !== 'open') {
            return;
        }

        $trade->trade_status = 'void';
        $trade->save();

        $trade->user->getWallet($trade->trade_wallet)->deposit(
            (float) $trade->trade_amount,
            ['description' => "Trade ID {$trade->id} voided by admin — stake refunded"]
        );

        event(new \App\Events\TradeUpdated($trade));
        $this->pushToTradeSocket($trade);
    }

    /**
     * Additive side-channel alongside the TradeUpdated broadcast above: pushes
     * the settled trade to node-services/tradesocket so a socket.io-connected
     * client gets the result over its already-open connection instead of
     * waiting on Ably/Reverb. Never lets a push failure affect settlement
     * itself — same try/catch-and-log-only pattern TradeController::placeTrade
     * already uses for its own broadcasts.
     */
    private function pushToTradeSocket(Trade $trade): void
    {
        $baseUrl = config('services.realtime.tradesocket_internal_url');
        $secret = config('services.realtime.api_secret');

        if (!$baseUrl || !$secret) {
            return;
        }

        try {
            Http::withHeaders(['X-Internal-Secret' => $secret])
                ->timeout(2)
                ->post("{$baseUrl}/internal/push/trade-updated", [
                    'user_id' => $trade->user_id, // routing only — tradesocket/index.js reads this to pick the socket room, it's not part of the card payload
                    ...\App\Events\TradeUpdated::payload($trade),
                ]);
        } catch (\Throwable $e) {
            Log::warning('Trade-socket settlement push failed', ['trade_id' => $trade->id, 'error' => $e->getMessage()]);
        }
    }
}
