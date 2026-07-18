<?php

namespace App\Console\Commands;

use App\Jobs\EvaluatePlanSubscription;
use App\Models\PlanSubscription;
use Illuminate\Console\Command;

class MaturePlans extends Command
{
    protected $signature = 'plans:mature';

    protected $description = 'Backstop for maturing plan subscriptions whose delayed evaluation job did not fire';

    public function handle()
    {
        $stragglers = PlanSubscription::where('status', 'active')
            ->where('matures_at', '<=', now())
            ->get();

        foreach ($stragglers as $subscription) {
            EvaluatePlanSubscription::dispatch($subscription->id);
        }

        $this->info("Queued {$stragglers->count()} matured subscription(s) for payout.");
    }
}
