<?php

namespace App\Listeners;

use App\Events\WalletBalanceUpdated;
use App\Models\User;
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Support\Facades\Log;

/**
 * Fires on every wallet balance change bavix/laravel-wallet makes,
 * regardless of cause (trade debit/credit, deposit, withdrawal, promo
 * redemption, admin credit, P2P, safe deposit, ...) — see
 * config/wallet.php's 'events.balance_updated' — and rebroadcasts it as
 * WalletBalanceUpdated so every open tab/device for that user updates
 * instantly. Registered in AppServiceProvider::boot().
 *
 * bavix/laravel-wallet dispatches BalanceUpdatedEvent SYNCHRONOUSLY as part
 * of the wallet's own debit/credit database transaction (see
 * AtomicService/LockService in vendor/bavix) — this listener runs inside
 * that transaction, not after it. A broadcast failure here (e.g. Ably
 * rate-limited/unreachable) must never be allowed to propagate: it would
 * abort the ENTIRE wallet transaction, making a real, sufficiently-funded
 * trade fail with a misleading "insufficient balance" error (debit_user()'s
 * blanket catch can't distinguish "actually insufficient" from "the
 * broadcast blew up") — confirmed happening in practice. The broadcast is
 * a nice-to-have side effect of the money movement, never a precondition
 * for it.
 */
class BroadcastWalletBalance
{
    public function handle(BalanceUpdatedEventInterface $event): void
    {
        $wallet = Wallet::find($event->getWalletId());
        if (! $wallet || $wallet->holder_type !== User::class) {
            return;
        }

        try {
            broadcast(new WalletBalanceUpdated((int) $wallet->holder_id, (string) $wallet->slug, (float) $event->getBalance()));
        } catch (\Throwable $e) {
            Log::warning('[BroadcastWalletBalance] broadcast failed — balance change itself still applied', [
                'wallet_id' => $wallet->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
