<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ReferralController extends Controller
{
    public function index()
    {
        $users = User::whereNotNull('referred_by')->with('referrer')->latest()->paginate(20);

        return view('admin.referrals.index', compact('users'));
    }

    public function show(User $user)
    {
        $downline = $user->referrals()->latest()->get();
        $commissions = $user->referralCommissions()->with('referredUser')->latest()->paginate(15);

        return view('admin.referrals.show', compact('user', 'downline', 'commissions'));
    }
}
