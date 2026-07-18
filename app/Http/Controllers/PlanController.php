<?php

namespace App\Http\Controllers;

use App\Jobs\EvaluatePlanSubscription;
use App\Models\Plan;
use App\Models\PlanSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $subscriptions = PlanSubscription::where('user_id', auth()->id())->latest()->get();

        return view('plans.index', compact('plans', 'subscriptions'));
    }

    public function show(Plan $plan): View
    {
        return view('plans.show', compact('plan'));
    }

    public function subscribe(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);
        $amount = (float) $validated['amount'];

        if ($plan->amount_type === 'fixed') {
            if (abs($amount - (float) $plan->fixed_amount) > 0.01) {
                return back()->with('error', 'This plan requires a fixed stake of ' . $plan->fixed_amount . '.');
            }
        } else {
            if ($amount < (float) $plan->min_amount || ($plan->max_amount && $amount > (float) $plan->max_amount)) {
                return back()->with('error', 'Stake amount is outside this plan\'s allowed range.');
            }
        }

        if (! debit_user($plan->wallet_slug, $amount, "Plan subscription: {$plan->name}")) {
            return back()->with('error', 'Insufficient balance to subscribe to this plan.');
        }

        $maturesAt = now()->addDays($plan->duration_days);
        $expectedPayout = $amount + ($amount * $plan->roi_percentage / 100);

        $subscription = PlanSubscription::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'stake_amount' => $amount,
            'roi_percentage' => $plan->roi_percentage,
            'expected_payout' => $expectedPayout,
            'wallet_slug' => $plan->wallet_slug,
            'starts_at' => now(),
            'matures_at' => $maturesAt,
            'status' => 'active',
        ]);

        EvaluatePlanSubscription::dispatch($subscription->id)->delay($maturesAt);

        (new \App\Services\ReferralCommissionService())->distribute(
            auth()->user(),
            'plan',
            $amount,
            $plan->wallet_slug,
            $subscription
        );

        return redirect()->route('plans.subscriptions')->with('success', "You've subscribed to {$plan->name}. Your payout matures on {$maturesAt->format('d M, Y')}.");
    }

    public function subscriptions(): View
    {
        $subscriptions = PlanSubscription::with('plan')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('plans.subscriptions', compact('subscriptions'));
    }

    public function reinvest(PlanSubscription $subscription): RedirectResponse
    {
        abort_unless($subscription->user_id === auth()->id(), 403);

        if ($subscription->status !== 'matured') {
            return back()->with('error', 'Only matured subscriptions can be reinvested.');
        }

        $plan = $subscription->plan;

        if ($plan->max_reinvest_count !== null && $subscription->reinvest_count >= $plan->max_reinvest_count) {
            return back()->with('error', 'This subscription has reached its maximum reinvest count.');
        }

        $stake = $subscription->expected_payout;

        // The matured subscription's payout was already credited to the
        // wallet by EvaluatePlanSubscription; reinvesting stakes it again,
        // so it must be debited back out (otherwise the user keeps the
        // payout *and* a fresh subscription for the same funds).
        if (! debit_user($plan->wallet_slug, $stake, "Reinvest into plan: {$plan->name}")) {
            return back()->with('error', 'Insufficient balance to reinvest.');
        }

        $maturesAt = now()->addDays($plan->duration_days);
        $expectedPayout = $stake + ($stake * $plan->roi_percentage / 100);

        $new = PlanSubscription::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'stake_amount' => $stake,
            'roi_percentage' => $plan->roi_percentage,
            'expected_payout' => $expectedPayout,
            'wallet_slug' => $plan->wallet_slug,
            'starts_at' => now(),
            'matures_at' => $maturesAt,
            'status' => 'active',
            'reinvest_count' => $subscription->reinvest_count + 1,
            'parent_subscription_id' => $subscription->id,
        ]);

        EvaluatePlanSubscription::dispatch($new->id)->delay($maturesAt);

        return redirect()->route('plans.subscriptions')->with('success', 'Reinvestment successful.');
    }
}
