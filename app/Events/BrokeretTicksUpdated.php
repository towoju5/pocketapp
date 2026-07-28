<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcasts a batch of raw Brokeret ticks on their own 'brokeret-feed'
 * channel — kept separate from AssetPriceBatchUpdated/'asset-prices' (the
 * main dashboard's iqcent-driven pipeline) so base_url/ui never shares a
 * channel, payload shape, or failure mode with it. ShouldBroadcastNow (not
 * ShouldBroadcast) so this fires immediately inline as ticks are received,
 * with no queue worker in the path — the whole point being the lowest
 * latency this app can offer from wire to browser.
 */
class BrokeretTicksUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    /** @param array<int, array{symbol: string, bid: float, ask: float, mid: float, category: string, t: int}> $ticks */
    public function __construct(public array $ticks)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('brokeret-feed')];
    }

    public function broadcastAs(): string
    {
        return 'ticks-updated';
    }

    public function broadcastWith(): array
    {
        return ['ticks' => $this->ticks];
    }
}
