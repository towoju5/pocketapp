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

class TradeUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trade;

    public function __construct(Trade $trade)
    {
        $this->trade = $trade;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('trade.updated');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->trade->id,
            'trade_close_time' => $this->trade->trade_close_time,
            'trade_currency' => $this->trade->trade_currency,
            'trade_status' => $this->trade->trade_status,
            'trade_amount' => $this->trade->trade_amount,
            'trade_profit' => $this->trade->trade_profit,
            'trade_percentage' => $this->trade->trade_percentage,
            'trade_direction' => $this->trade->trade_direction,
            'start_price' => $this->trade->start_price,
        ];
    }
}
