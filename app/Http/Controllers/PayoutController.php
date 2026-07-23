<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FAQRCode\Google2FA;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::whereUserId(auth()->id())->latest()->get();
        return view('payout.index', compact('payouts'));
    }

    public function create()
    {
        $kycVerified = auth()->user()->kyc?->status === 'verified';

        return view('payout.create', compact('kycVerified'));
    }

    public function store(Request $request, Google2FA $google2fa)
    {
        $user = $request->user();

        $validate = Validator::make($request->all(), [
            'amount'    => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'address' => 'required|string',
            // Withdrawals get their own fresh OTP check on top of the
            // once-per-session login challenge — money movement is more
            // sensitive than just being logged in, and a code entered
            // minutes/hours ago at login shouldn't authorize it alone.
            'one_time_password' => $user->google2fa_enabled ? ['required', 'string'] : ['sometimes'],
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $validated = $validate->validated();

        if ($user->google2fa_enabled && !$google2fa->verifyKey($user->google2fa_secret, $validated['one_time_password'])) {
            return back()->withErrors(['one_time_password' => 'Invalid authentication code.'])->withInput();
        }

        $payout = new Payout();

        if(!debit_user('qt_real_usd', $validated['amount'], "Customer Payout")) {
            return back()->with('error', 'Insufficient balance in your account.');
        }

        $payout->user_id           = auth()->id();
        $payout->payout_amount     = $request->amount;
        $payout->payout_date_time  = now();
        $payout->payout_status     = "pending";
        $payout->payout_method     = $request->payment_method;
        $payout->payout_bonus      = $request->address;
        $payout->payout_extra_info = $request->except(['_token', 'one_time_password']);

        if ($payout->save()) {
            return back()->with('success', 'Payout request submitted and pending admin review.');
        }
    }

    public function show(Payout $payout)
    {
        return view('payout.show', compact('payout'));
    }
}
