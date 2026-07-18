<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\ReferralCommissionRate;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReferralCommissionService
{
    /**
     * Walk up to 3 levels of the referred user's upline, crediting each
     * ancestor a commission (if an active rate exists for that level and
     * activity type) on the given base amount. Idempotent per
     * (commissionable, level) so retried/duplicate triggers don't double-pay.
     */
    public function distribute(User $referredUser, string $activityType, float $baseAmount, string $walletSlug, Model $commissionable): void
    {
        $ancestor = $referredUser->referrer;

        for ($level = 1; $level <= 3 && $ancestor; $level++) {
            $rate = ReferralCommissionRate::where('level', $level)
                ->where('activity_type', $activityType)
                ->where('is_active', true)
                ->first();

            if ($rate) {
                $alreadyPaid = ReferralCommission::where('commissionable_type', $commissionable->getMorphClass())
                    ->where('commissionable_id', $commissionable->getKey())
                    ->where('level', $level)
                    ->exists();

                if (! $alreadyPaid) {
                    $commissionAmount = round($baseAmount * $rate->percentage / 100, 2);

                    if ($commissionAmount > 0) {
                        $ancestor->getWallet($walletSlug)->deposit($commissionAmount, [
                            'description' => "Referral commission (L{$level}, {$activityType}) from {$referredUser->first_name}",
                        ]);

                        ReferralCommission::create([
                            'beneficiary_id' => $ancestor->id,
                            'referred_user_id' => $referredUser->id,
                            'level' => $level,
                            'activity_type' => $activityType,
                            'commissionable_type' => $commissionable->getMorphClass(),
                            'commissionable_id' => $commissionable->getKey(),
                            'base_amount' => $baseAmount,
                            'percentage' => $rate->percentage,
                            'commission_amount' => $commissionAmount,
                            'wallet_slug' => $walletSlug,
                        ]);
                    }
                }
            }

            $ancestor = $ancestor->referrer;
        }
    }
}
