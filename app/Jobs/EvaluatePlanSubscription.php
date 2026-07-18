<?php

namespace App\Jobs;

use App\Models\PlanSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class EvaluatePlanSubscription implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected $subscriptionId;

    public function __construct(int $subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function handle(): void
    {
        $subscription = PlanSubscription::find($this->subscriptionId);

        if (! $subscription || $subscription->status !== 'active') {
            return;
        }

        try {
            // credit_user()/debit_user() operate on auth()->user(), which
            // doesn't exist in a queued job — credit the subscription's
            // actual owner directly via their wallet instead.
            $wallet = $subscription->user->getWallet($subscription->wallet_slug);
            $wallet->deposit($subscription->expected_payout, ['description' => "Plan matured: subscription #{$subscription->id}"]);

            $subscription->status = 'matured';
            $subscription->payout_at = now();
            $subscription->save();

            $subscription->user->notify(new \App\Notifications\GenericNotification(
                'Plan matured',
                "Your subscription to \"{$subscription->plan?->name}\" matured. " . number_format($subscription->expected_payout, 2) . ' credited to your wallet.',
                route('plans.subscriptions')
            ));
        } catch (\Throwable $th) {
            Log::error('Failed to evaluate plan subscription ' . $this->subscriptionId . ': ' . $th->getMessage());
        }
    }
}
