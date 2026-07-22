<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Replaces per-tick AssetPriceUpdated broadcasts — the collector now flushes
 * ticks in batches (see collector/index.js), so one broadcast carries every
 * tick from that flush instead of firing one WS push per tick across the
 * whole ~150-asset catalog.
 */
class AssetPriceBatchUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    /** @param array<int, array{symbol: string, price: float, t: int}> $ticks */
    public function __construct(public array $ticks)
    {
    }

    public function broadcastOn(): array
    {
        return [new Channel('asset-prices')];
    }

    public function broadcastAs(): string
    {
        return 'prices-updated';
    }

    public function broadcastWith(): array
    {
        return ['ticks' => $this->ticks];
    }
}
