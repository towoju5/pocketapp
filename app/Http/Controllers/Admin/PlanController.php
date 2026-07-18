<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->paginate(15);

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');
    }

    public function show(Plan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $plan->update($this->validated($request));

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'This plan has active subscriptions and cannot be deleted.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'amount_type' => 'required|in:fixed,range',
            'fixed_amount' => 'nullable|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'roi_percentage' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'capital_lock' => 'nullable|boolean',
            'daily_task_limit' => 'nullable|integer|min:0',
            'max_reinvest_count' => 'nullable|integer|min:0',
            'fee_discount_percentage' => 'nullable|numeric|min:0|max:100',
            'badge_color' => 'nullable|string|max:20',
            'wallet_slug' => 'required|string|max:50',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
