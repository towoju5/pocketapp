<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Flasher\Laravel\Facade\Flasher;

class DepositController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $deposits = Deposit::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        $wallets = Auth::user()->wallets;
        return view('deposits.create', compact('wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'deposit_amount' => 'required|numeric|min:1',
            'deposit_method' => 'required|string'
        ]);

        $wallet = Wallet::findOrFail($request->wallet_id);
        
        // Calculate bonus (example: 5% bonus for deposits over 1000)
        $bonus = $request->deposit_amount >= 1000 ? $request->deposit_amount * 0.05 : 0;

        $deposit = new Deposit([
            'user_id' => Auth::id(),
            'wallet_id' => $request->wallet_id,
            'deposit_amount' => $request->deposit_amount,
            'deposit_date_time' => Carbon::now(),
            'deposit_status' => 'pending',
            'deposit_method' => $request->deposit_method,
            'deposit_extra_info' => $request->extra_info ? json_encode($request->extra_info) : null,
            'deposit_bonus' => $bonus
        ]);

        $deposit->save();

        // Process the deposit using Bavix Wallet
        try {
            $user = Auth::user();
            $user->deposit($request->deposit_amount + $bonus);
            
            $deposit->update(['deposit_status' => 'completed']);
            
            Flasher::addSuccess('Deposit processed successfully!');
            return redirect()->route('deposits.show', $deposit->id);
            
        } catch (\Exception $e) {
            $deposit->update(['deposit_status' => 'failed']);
            Flasher::addError('Deposit processing failed. Please try again.');
            return back();
        }
    }

    public function show(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('deposits.show', compact('deposit'));
    }

    public function getDepositHistory()
    {
        $deposits = Deposit::where('user_id', Auth::id())
            ->with('wallet')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $deposits
        ]);
    }

    public function cancelDeposit(Deposit $deposit)
    {
        if ($deposit->user_id !== Auth::id() || $deposit->deposit_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Unable to cancel this deposit'
            ], 403);
        }

        $deposit->update(['deposit_status' => 'cancelled']);

        Flasher::addSuccess('Deposit cancelled successfully');
        return redirect()->route('deposits.index');
    }

    public function getDepositStats()
    {
        $stats = [
            'total_deposits' => Deposit::where('user_id', Auth::id())
                ->where('deposit_status', 'completed')
                ->sum('deposit_amount'),
            'total_bonus' => Deposit::where('user_id', Auth::id())
                ->where('deposit_status', 'completed')
                ->sum('deposit_bonus'),
            'pending_deposits' => Deposit::where('user_id', Auth::id())
                ->where('deposit_status', 'pending')
                ->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
