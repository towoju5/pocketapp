<?php

namespace App\Services\Payments;

use App\Models\Deposit;
use App\Models\PaymentProvider;
use Illuminate\Support\Facades\DB;

/**
 * Shared by every gateway's handleWebhook(): find the Deposit a webhook
 * event refers to, and credit it exactly once. Wallet-crediting logic is
 * intentionally NOT app/Helpers/helper.php's credit_user() — that helper
 * reads auth()->user(), which doesn't exist in an unauthenticated
 * server-to-server webhook request.
 *
 * Uses bavix/laravel-wallet's depositFloat() (not deposit()) deliberately:
 * deposit() takes the wallet's *raw* integer subunits directly, while
 * depositFloat() scales a human-facing amount by the wallet's
 * decimal_places first. qt_real_usd has decimal_places=2, so deposit(25)
 * stores 25 raw units (i.e. $0.25) — only depositFloat(25) correctly
 * credits $25.00. This matters here specifically because a webhook amount
 * is real money already charged by an external gateway; crediting the
 * wrong scale would under/over-credit the user relative to what they
 * were actually charged.
 */
trait CreditsDepositOnce
{
    protected function findDepositByReference(PaymentProvider $provider, string $reference): ?Deposit
    {
        return Deposit::where('deposit_method', $provider->slug)
            ->where('deposit_extra_info->reference', $reference)
            ->first();
    }

    /**
     * Locks the row for the duration of the credit so two overlapping
     * webhook deliveries (retries, or duplicate events) for the same
     * Deposit can never double-credit the wallet.
     */
    protected function creditDepositOnce(Deposit $deposit, PaymentProvider $provider): void
    {
        DB::transaction(function () use ($deposit, $provider) {
            $locked = Deposit::whereKey($deposit->id)->lockForUpdate()->first();

            if (! $locked || $locked->deposit_status === 'completed') {
                return;
            }

            $locked->user->getWallet('qt_real_usd')->depositFloat((float) $locked->deposit_amount, [
                'description' => "Deposit via {$provider->display_name}",
            ]);

            $locked->update(['deposit_status' => 'completed']);
        });
    }
}
