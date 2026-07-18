<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommissionRate;
use Illuminate\Http\Request;

class ReferralCommissionRateController extends Controller
{
    public function index()
    {
        $rates = ReferralCommissionRate::orderBy('activity_type')->orderBy('level')->get();

        return view('admin.referral-rates.index', compact('rates'));
    }

    public function create()
    {
        return view('admin.referral-rates.create');
    }

    public function store(Request $request)
    {
        ReferralCommissionRate::create($this->validated($request));

        return redirect()->route('admin.referral-rates.index')->with('success', 'Rate created successfully.');
    }

    public function edit(ReferralCommissionRate $referralRate)
    {
        return view('admin.referral-rates.edit', ['rate' => $referralRate]);
    }

    public function update(Request $request, ReferralCommissionRate $referralRate)
    {
        $referralRate->update($this->validated($request));

        return redirect()->route('admin.referral-rates.index')->with('success', 'Rate updated successfully.');
    }

    public function destroy(ReferralCommissionRate $referralRate)
    {
        $referralRate->delete();

        return redirect()->route('admin.referral-rates.index')->with('success', 'Rate deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'level' => 'required|integer|min:1|max:3',
            'activity_type' => 'required|in:trade,plan',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
