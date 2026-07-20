<?php

namespace App\Services;

use App\Models\User;
use App\Models\CashbackRule;
use App\Models\Trade;
use App\Models\ExpressTrade;

class CashbackService
{
    /**
     * Rebates a percentage of a losing trade's stake back to the same wallet
     * it was debited from. Called from EvaluateTrade/ExpressTradeJob right
     * after a trade settles as a loss.
     */
    public function applyLossCashback(User $user, Trade|ExpressTrade $trade): bool
    {
        if ($trade->trade_status !== 'lose') {
            return false;
        }

        $rule = CashbackRule::where('type', 'loss')->where('active', true)->first();
        if (!$rule) {
            return false;
        }

        $amount = ($rule->percentage / 100) * (float) $trade->trade_amount;
        if ($amount <= 0) {
            return false;
        }

        $user->getWallet($trade->trade_wallet)->deposit($amount, [
            'description' => "Loss cashback ({$rule->percentage}%) for trade #{$trade->id}",
        ]);

        return true;
    }

    public function applyVolumeCashback(User $user)
    {
        $rule = CashbackRule::where('type', 'volume')->where('active', true)->first();
        if ($rule) {
            $volume = Trade::where('user_id', $user->id)->sum('trade_amount');

            if ($volume >= $rule->volume_threshold) {
                $amount = ($rule->percentage / 100) * $volume;
                $user->getWallet($user->trade_wallet ?? 'qt_demo_usd')->deposit($amount, [
                    'description' => "Volume cashback ({$rule->percentage}%)",
                ]);
            }
        }
    }

    public function applyPromoCashback(User $user, string $promoCode)
    {
        $rule = CashbackRule::where('type', 'promo')
            ->where('promo_code', $promoCode)
            ->where('active', true)
            ->first();

        if ($rule) {
            $amount = ($rule->percentage / 100) * 100; // base amount for promo cashback
            $user->getWallet($user->trade_wallet ?? 'qt_demo_usd')->deposit($amount, [
                'description' => "Promo cashback: {$promoCode}",
            ]);
        }
    }

    /** The single active loss-rebate rule, if any — this is what drives the customer-facing Cashback page. */
    public function activeLossRule(): ?CashbackRule
    {
        return CashbackRule::where('type', 'loss')->where('active', true)->first();
    }
}
