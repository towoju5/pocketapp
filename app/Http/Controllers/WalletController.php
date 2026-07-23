<?php

namespace App\Http\Controllers;

use App\Models\Bitgo;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (empty($user->wallets)) {
            create_user_wallet($user->id);
        }

        $methods = Bitgo::where('can_deposit', true)->get();
        $kycVerified = $user->kyc?->status === 'verified';
        $tab = request('tab', 'deposit');

        return view('wallet.index', compact('methods', 'kycVerified', 'tab'));
    }

    /**
     * Tops a demo wallet back up to $10,000 — practice-mode only, never
     * touches a real-money wallet. Only does anything when the balance is
     * actually below that (a deposit for the shortfall), so it can't be used
     * to pull a winning demo balance back down.
     */
    public function resetDemoBalance()
    {
        $user = Auth::user();
        $slug = $user->active_wallet_slug ?? 'qt_demo_usd';

        if (!is_demo_wallet($slug)) {
            return back()->with('error', 'Only demo wallets can be reset.');
        }

        $wallet = $user->getWallet($slug);
        $target = 10000.0;

        if ($wallet->balance >= $target) {
            return back()->with('error', 'Your demo balance is already at or above $10,000.');
        }

        $wallet->deposit($target - (float) $wallet->balance, ['description' => 'Demo balance reset to $10,000']);

        return back()->with('success', 'Demo balance reset to $10,000.');
    }
}
