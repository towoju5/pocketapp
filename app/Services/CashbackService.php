<?php

namespace App\Services;

use App\Models\User;
use App\Models\CashbackRule;
use App\Models\CashbackPayout;
use App\Models\PromoCodeRedemption;
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

    /**
     * Rebates a percentage of a user's monthly trade volume, evaluated once
     * per calendar month per wallet (real/demo tracked separately so demo
     * volume can never earn real-money cashback). Safe to call repeatedly
     * (e.g. from a daily scheduled command) — a CashbackPayout row per
     * (user, rule, month, wallet) guards against paying the same month twice.
     */
    public function applyVolumeCashback(User $user): bool
    {
        $rule = CashbackRule::where('type', 'volume')->where('active', true)->first();
        if (!$rule) {
            return false;
        }

        $period = now()->format('Y-m');
        $applied = false;

        foreach (['qt_real_usd', 'qt_demo_usd'] as $walletSlug) {
            $periodKey = "{$period}:{$walletSlug}";

            $alreadyPaid = CashbackPayout::where('user_id', $user->id)
                ->where('cashback_rule_id', $rule->id)
                ->where('period_key', $periodKey)
                ->exists();
            if ($alreadyPaid) {
                continue;
            }

            $volume = Trade::where('user_id', $user->id)
                ->where('trade_wallet', $walletSlug)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('trade_amount');

            if ($volume < $rule->volume_threshold) {
                continue;
            }

            $amount = ($rule->percentage / 100) * $volume;
            if ($amount <= 0) {
                continue;
            }

            $user->getWallet($walletSlug)->deposit($amount, [
                'description' => "Volume cashback ({$rule->percentage}%) for " . now()->format('F Y'),
            ]);

            CashbackPayout::create([
                'user_id' => $user->id,
                'cashback_rule_id' => $rule->id,
                'type' => 'volume',
                'period_key' => $periodKey,
                'amount' => $amount,
            ]);

            $applied = true;
        }

        return $applied;
    }

    /**
     * Rebates a percentage of what a promo-code redemption actually credited
     * the user, on top of the promo code's own bonus. Called once, right
     * after a redemption is created — a CashbackPayout row tied to the
     * redemption id keeps this idempotent even if ever called twice.
     */
    public function applyPromoCashback(User $user, string $promoCode, PromoCodeRedemption $redemption): bool
    {
        $rule = CashbackRule::where('type', 'promo')
            ->where('promo_code', $promoCode)
            ->where('active', true)
            ->first();

        if (!$rule) {
            return false;
        }

        $alreadyPaid = CashbackPayout::where('promo_code_redemption_id', $redemption->id)->exists();
        if ($alreadyPaid) {
            return false;
        }

        $amount = ($rule->percentage / 100) * (float) $redemption->amount_credited;
        if ($amount <= 0) {
            return false;
        }

        // Promo redemptions always credit the real wallet (see
        // PromoCodeController::redeem()), so the cashback on top of it does too.
        $user->getWallet('qt_real_usd')->deposit($amount, [
            'description' => "Promo cashback ({$rule->percentage}%) for code {$promoCode}",
        ]);

        CashbackPayout::create([
            'user_id' => $user->id,
            'cashback_rule_id' => $rule->id,
            'type' => 'promo',
            'promo_code_redemption_id' => $redemption->id,
            'amount' => $amount,
        ]);

        return true;
    }

    /** The single active loss-rebate rule, if any — this is what drives the customer-facing Cashback page. */
    public function activeLossRule(): ?CashbackRule
    {
        return CashbackRule::where('type', 'loss')->where('active', true)->first();
    }
}
