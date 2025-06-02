<?php

namespace App\Services;

use App\Models\User;
use App\Models\CashbackRule;
use App\Models\Trade;

class CashbackService
{
    public function applyLossCashback(User $user, Trade $trade)
    {
        if ($trade->result === 'loss') {
            $rule = CashbackRule::where('type', 'loss')->where('active', true)->first();
            if ($rule) {
                $amount = ($rule->percentage / 100) * $trade->amount;
                $user->deposit($amount, ['meta' => 'Loss Cashback']);
            }
        }
    }

    public function applyVolumeCashback(User $user)
    {
        $rule = CashbackRule::where('type', 'volume')->where('active', true)->first();
        if ($rule) {
            $volume = Trade::where('user_id', $user->id)->sum('amount');

            if ($volume >= $rule->volume_threshold) {
                $amount = ($rule->percentage / 100) * $volume;
                $user->deposit($amount, ['meta' => 'Volume Cashback']);
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
            $user->deposit($amount, ['meta' => 'Promo Cashback: ' . $promoCode]);
        }
    }
}


// usage is as below

/*
// In TradeController or TradeService

use App\Services\CashbackService;

$cashback = new CashbackService();
$cashback->applyLossCashback($user, $trade);


// In a scheduled job or manual trigger

$users = User::all();
$cashback = new CashbackService();

foreach ($users as $user) {
    $cashback->applyVolumeCashback($user);
}


$cashback->applyPromoCashback($user, 'WELCOME10');

*/
