<?php

namespace App\Events;

use App\Models\Trade;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradeUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trade;

    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('trades.user.' . $this->trade->user_id);
    }

    public function broadcastWith()
    {
        return self::payload($this->trade);
    }

    /**
     * The shape tradeCards.js's updateOrInsertTradeCard() actually reads
     * (event.id/html/trade_status/wallet_balance/trade_wallet). Shared with
     * TradeSettlementService::pushToTradeSocket() so the socket.io push and
     * this Echo broadcast can never drift into sending different shapes for
     * the same underlying update.
     */
    public static function payload(Trade $trade): array
    {
        return [
            'id' => $trade->id,
            'trade_close_time' => $trade->trade_close_time,
            'trade_currency' => $trade->trade_currency,
            'trade_status' => $trade->trade_status,
            'trade_amount' => $trade->trade_amount,
            'trade_profit' => $trade->trade_profit,
            'trade_percentage' => $trade->trade_percentage,
            'trade_direction' => $trade->trade_direction,
            'start_price' => $trade->start_price,
            'trade_wallet' => $trade->trade_wallet,
            // Lets the frontend update the topbar balance the instant it
            // actually changes — not just on win/lose settlement, but also
            // right when a trade is first placed: the stake is debited
            // immediately at that point (see TradeController::placeTrade),
            // it isn't held until the trade closes.
            'wallet_balance' => (float) $trade->user->getWallet($trade->trade_wallet)->balance,
            'html' => view('mini-pages.trade-list', ['trade' => $trade])->render(),
        ];
    }
}
