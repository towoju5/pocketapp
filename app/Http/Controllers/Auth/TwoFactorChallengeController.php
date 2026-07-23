<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PragmaRX\Google2FAQRCode\Google2FA;

class TwoFactorChallengeController extends Controller
{
    public function show(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        if (!$request->user()->google2fa_enabled || session('2fa_passed')) {
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request, Google2FA $google2fa)
    {
        $validated = $request->validate([
            'one_time_password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (!$user->google2fa_secret || !$google2fa->verifyKey($user->google2fa_secret, $validated['one_time_password'])) {
            return back()->withErrors(['one_time_password' => 'Invalid authentication code.']);
        }

        $request->session()->put('2fa_passed', true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
