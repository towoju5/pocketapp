<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanSubscription;
use Illuminate\Http\Request;

class PlanSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = PlanSubscription::with(['user', 'plan'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.plan-subscriptions.index', compact('subscriptions'));
    }

    public function show(PlanSubscription $planSubscription)
    {
        return view('admin.plan-subscriptions.show', ['subscription' => $planSubscription]);
    }

    public function cancel(PlanSubscription $planSubscription)
    {
        if ($planSubscription->status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be cancelled.');
        }

        // credit_user() operates on auth()->user(), which here would be the
        // admin, not the subscription owner — refund the actual owner's
        // wallet directly instead.
        $planSubscription->user
            ->getWallet($planSubscription->wallet_slug)
            ->deposit($planSubscription->stake_amount, ['description' => "Admin refund: cancelled subscription #{$planSubscription->id}"]);

        $planSubscription->update(['status' => 'cancelled']);

        return back()->with('success', 'Subscription cancelled and stake refunded.');
    }
}
