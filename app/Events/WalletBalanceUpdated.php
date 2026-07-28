<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Broadcasts a wallet's new balance to every device/tab the owning user has
 * open, the instant it changes — for ANY reason (trade debit/credit,
 * deposit, withdrawal, promo redemption, admin credit, P2P, safe deposit,
 * etc.), not just trade placement/settlement (see TradeUpdated, which only
 * covers that one path). Fired from a listener on bavix/laravel-wallet's own
 * BalanceUpdatedEvent (app/Listeners/BroadcastWalletBalance.php), so every
 * wallet mutation gets this for free with no per-controller wiring. Reuses
 * the existing 'trades.user.{id}' private channel (already subscribed to on
 * every authenticated page) rather than adding a new one. ShouldBroadcastNow
 * (not ShouldBroadcast) so this doesn't depend on a queue worker running.
 */
class WalletBalanceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public int $userId,
        public string $walletSlug,
        public float $balance,
    ) {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('trades.user.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'balance-updated';
    }

    public function broadcastWith(): array
    {
        return [
            'wallet_slug' => $this->walletSlug,
            'balance' => $this->balance,
        ];
    }
}
