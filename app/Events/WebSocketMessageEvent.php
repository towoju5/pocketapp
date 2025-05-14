<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class WebSocketMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        // Broadcast the event on a specific channel
        return new Channel('websocket-channel');
    }

    public function broadcastAs()
    {
        // Name of the event being broadcast
        return 'websocket.message';
    }
}
