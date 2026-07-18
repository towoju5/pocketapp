<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ReferralController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $directCount = $user->referrals()->count();
        $level2Count = \App\Models\User::whereIn('referred_by', $user->referrals()->pluck('id'))->count();
        $level3Count = \App\Models\User::whereIn('referred_by',
            \App\Models\User::whereIn('referred_by', $user->referrals()->pluck('id'))->pluck('id')
        )->count();

        $totalEarned = $user->referralCommissions()->sum('commission_amount');

        $commissions = $user->referralCommissions()
            ->with('referredUser')
            ->latest()
            ->paginate(15);

        $directReferrals = $user->referrals()->latest()->limit(10)->get();

        return view('referrals.index', compact(
            'user', 'directCount', 'level2Count', 'level3Count', 'totalEarned', 'commissions', 'directReferrals'
        ));
    }
}
