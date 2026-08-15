<?php

namespace App\Services\Kyc;

use App\Models\KycProvider;
use App\Models\KycVerification;
use App\Notifications\GenericNotification;
use Illuminate\Support\Facades\DB;

/**
 * Shared by every hosted provider's handleWebhook(): find the
 * KycVerification a webhook event refers to, and settle it exactly once —
 * mirrors Payments\CreditsDepositOnce, same reasoning (lock the row so two
 * overlapping webhook deliveries for the same applicant/session can't race
 * each other into an inconsistent state).
 */
trait SettlesKycVerificationOnce
{
    protected function findKycByReference(KycProvider $provider, string $reference): ?KycVerification
    {
        return KycVerification::where('provider', $provider->slug)
            ->where('provider_reference', $reference)
            ->first();
    }

    /**
     * Settles the row to 'verified' or 'rejected' exactly once — a second
     * webhook for an already-decided applicant (retry, duplicate event) is
     * a no-op rather than re-notifying the user or overwriting a decision.
     * reviewed_by stays null (no admin reviewed this — the provider did),
     * distinguishing hosted-provider decisions from manual admin ones in
     * admin/kyc/show.blade.php.
     */
    protected function settleKycOnce(KycVerification $kyc, string $status, ?string $reason = null): void
    {
        DB::transaction(function () use ($kyc, $status, $reason) {
            $locked = KycVerification::whereKey($kyc->id)->lockForUpdate()->first();

            if (! $locked || $locked->status === 'verified' || $locked->status === 'rejected') {
                return;
            }

            $locked->update([
                'status' => $status,
                'rejection_reason' => $status === 'rejected' ? $reason : null,
                'reviewed_at' => now(),
            ]);

            $locked->user->notify(new GenericNotification(
                $status === 'verified' ? 'Identity verified' : 'Identity verification rejected',
                $status === 'verified'
                    ? 'Your identity has been verified. Withdrawals are now unlocked.'
                    : ($reason ?: 'Your identity verification was not approved.'),
                route('kyc.show')
            ));
        });
    }
}
