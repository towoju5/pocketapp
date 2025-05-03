<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TradeDataReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tradeData;

    public function __construct(array $tradeData)
    {
        $this->tradeData = $tradeData;
    }

    public function broadcastOn()
    {
        return new Channel('trades-websocket');
    }

    public function broadcastAs()
    {
        return 'TradeDataReceived';
    }
}