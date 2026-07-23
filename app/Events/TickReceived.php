<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow as BroadcastingShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TickReceived implements BroadcastingShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $symbol,
        public array $tick
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel("ticks.{$this->symbol}");
    }

    public function broadcastAs(): string
    {
        return 'tick';
    }

    public function broadcastWith(): array
    {
        return $this->tick;
    }
}
