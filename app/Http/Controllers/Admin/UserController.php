<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
    
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'username'   => 'required|string|unique:users,username,' . $user->id,
            'phone'      => 'nullable|string|max:20',
        ]);
    
        $user->update($validated);
    
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
    

    /**
     * Handle wallet credit or debit for a given user.
     */
    public function updateWalletAction(Request $request, $userId)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'wallet' => 'required|string',
            'action' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if($validator->fails()) {
            var_dump($validator->errors()); exit;
            return back()->with($validator->errors())->withInput();
        }

        $user = User::findorFail($userId);

        $walletName = $request->input('wallet');
        $action = $request->input('action');
        $amount = $request->input('amount');

        // Get user's wallet by name (slug)
        $wallet = $user->getWallet($walletName);

        if (!$wallet) {
            return redirect()->back()->withErrors(['wallet' => 'Selected wallet not found.']);
        }

        try {
            if ($action === 'credit') {
                // Deposit amount
                $t = $wallet->deposit($amount);
                
                $message = "Successfully credited ₦{$amount} to {$walletName} wallet.";
            } else {
                // Check balance before withdrawal
                if ($wallet->balanceFloat < $amount) {
                    return redirect()->back()->withErrors(['amount' => 'Insufficient balance for debit.']);
                }
                // Withdraw amount
                $wallet->withdraw($amount);
                $message = "Successfully debited ₦{$amount} from {$walletName} wallet.";
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Transaction failed: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', $message);
    }
}
