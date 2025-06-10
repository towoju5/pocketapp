<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class WalletController extends Controller
{
    public function index()
    {
        $users = User::with('wallets')->get();
        return view('admin.wallets.index', compact('users'));
    }

    public function credit(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'wallet' => 'required|string',
        ]);

        $user   = User::findOrFail($userId);
        $wallet = $user->getWallet($request->wallet);
        $wallet->deposit($request->amount);

        return back()->with('success', 'Wallet credited successfully!');
    }

    public function debit(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'wallet' => 'required|string',
        ]);

        $user   = User::findOrFail($userId);
        $wallet = $user->getWallet($request->wallet);

        if ($wallet->canWithdraw($request->amount)) {
            $wallet->withdraw($request->amount);
            return back()->with('success', 'Wallet debited successfully!');
        }

        return back()->with('error', 'Insufficient funds in wallet.');
    }
}
