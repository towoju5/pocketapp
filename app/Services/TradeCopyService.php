<?php

namespace App\Services;

use App\Jobs\EvaluateTrade;
use App\Models\Trade;
use App\Models\TraderFollow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Mirrors a leader's trade into every active follower's own account at a
 * fixed stake (set per-follow, not proportional to the leader's amount) —
 * same asset/direction/entry price/close time, debited from each follower's
 * own currently-active wallet so demo followers never touch real trades
 * (and vice versa).
 */
class TradeCopyService
{
    public function mirror(Trade $leaderTrade): void
    {
        $followers = TraderFollow::where('trader_id', $leaderTrade->user_id)
            ->where('is_active', true)
            ->with('follower')
            ->get();

        if ($followers->isEmpty()) {
            return;
        }

        $copiedCount = 0;

        foreach ($followers as $follow) {
            try {
                if ($this->mirrorForFollower($leaderTrade, $follow)) {
                    $copiedCount++;
                }
            } catch (\Throwable $e) {
                Log::error('TradeCopyService: mirror failed', [
                    'follow_id' => $follow->id,
                    'leader_trade_id' => $leaderTrade->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($copiedCount > 0) {
            $leaderTrade->increment('trade_copied_count', $copiedCount);
        }
    }

    private function mirrorForFollower(Trade $leaderTrade, TraderFollow $follow): bool
    {
        $follower = $follow->follower;
        if (!$follower || $follower->id === $leaderTrade->user_id) {
            return false;
        }

        create_user_wallet($follower->id);
        $walletSlug = $follower->trade_wallet ?? 'qt_demo_usd';
        $amount = (float) $follow->copy_stake_amount;

        // debit_user() operates on auth()->user(), which here would be the
        // leader (this runs inside the leader's request) — withdraw directly
        // from the follower's own wallet instead, same reasoning as
        // EvaluateTrade's use of $trade->user->getWallet(...) over credit_user().
        $wallet = $follower->getWallet($walletSlug);
        if (!$wallet || !$wallet->withdraw($amount, ['description' => "Copy trade from user #{$leaderTrade->user_id}"])) {
            return false;
        }

        $percentageProfit = (float) $leaderTrade->trade_percentage;
        $profitAmount = $percentageProfit * $amount;
        $closeTime = Carbon::parse($leaderTrade->trade_close_time);

        $copiedTrade = Trade::create([
            'trade_currency' => $leaderTrade->trade_currency,
            'trade_direction' => $leaderTrade->trade_direction,
            'trade_amount' => $amount,
            'trade_close_time' => $closeTime,
            'trade_extra_info' => [
                'copied_from_trader' => $leaderTrade->user_id,
                'source_trade_id' => $leaderTrade->id,
            ],
            'start_price' => $leaderTrade->start_price,
            'trade_status' => 'pending',
            'trade_copied_count' => 0,
            'user_id' => $follower->id,
            'trade_wallet' => $walletSlug,
            'trade_profit' => $amount + $profitAmount,
            'trade_percentage' => $percentageProfit,
        ]);

        event(new \App\Events\NewTradeCreated($copiedTrade));
        event(new \App\Events\TradeUpdated($copiedTrade));
        EvaluateTrade::dispatch($copiedTrade)->delay($closeTime);

        return true;
    }
}
