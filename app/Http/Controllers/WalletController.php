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
}
