<?php

namespace App\Events;

use App\Models\Trade;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewTradeCreated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    private Trade $trade;

    /**
     * Create a new event instance.
     */
    public function __construct(Trade $trade)
    {
        $this->trade = $trade;

        // Convert the trade model to an array for logging
        Log::info('Broadcasting NewTradeCreated event', ['trade_1' => $trade->toArray()]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info('Broadcasting NewTradeCreated event', ['trade_2' => $this->trade->toArray()]);
        return [
            new Channel('trade.created'),
        ];
    }

    /**
     * Get the data to broadcast with the event.
     */
    public function broadcastWith(): array
    {
        Log::info('Broadcasting NewTradeCreated event', ['trade_4' => $this->trade->toArray()]);
        return $this->trade->toArray(); // Ensure broadcast data is complete
    }

    public function broadcastAs(): string
    {
        Log::info('Broadcasting NewTradeCreated event', ['trade_3' => $this->trade->toArray()]);
        return 'trade.created';
    }
}
