<?php

namespace App\Events;

use App\Models\Signal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SignalCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $signal;

    public function __construct(Signal $signal)
    {
        $this->signal = $signal;
    }

    public function broadcastOn()
    {
        return new Channel('signals');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->signal->id,
            'asset' => $this->signal->asset,
            'direction' => $this->signal->direction,
            'amount' => $this->signal->amount,
            'duration' => $this->signal->duration
        ];
    }
}

