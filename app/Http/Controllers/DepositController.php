<?php

namespace App\Http\Controllers;

use App\Models\Bitgo;
use App\Models\BitgoWallets;
use App\Models\Deposit;
use App\Models\User;
// use App\Models\Wallet;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Flasher\Laravel\Facade\Flasher;

class DepositController extends Controller
{
    public $wallets;
    public function __construct()
    {
        $this->wallets = auth()->user()->wallets();
        if (empty($this->wallets)) {
            // then create the user wallets
            create_user_wallet();
            // var_dump($this->wallets);
        }
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
        $user = Auth::user();
        $wallets = $user->wallets;
        if (empty($wallets)) {
            create_user_wallet($user->id);
        }

        $methods = Bitgo::where('can_deposit', true)->get();
        return view('deposits.create', compact('wallets', 'methods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "deposit_step" => "required|in:1,2,3,4",
        ]);

        if ($request->deposit_step == 1) {
            $request->validate([
                'deposit_method' => 'required|string'
            ]);

            // Validate and find the selected deposit method
            $deposit_method = Bitgo::whereId($request->deposit_method)->where('can_deposit', true)->first();
            if (!$deposit_method) {
                return response()->json(['message' => 'Invalid deposit method.'], 422);
            }

            // // Update session with the newly selected deposit method
            // session()->put('deposit_method', $deposit_method);
            // session()->put('deposit_method_id', $request->deposit_method);
			$deposit_method_id = $request->deposit_method;
            // After updating the session, return to step 2 with the new deposit method
            return view('deposits.partials.step-2', compact('deposit_method', 'deposit_method_id'));
        }

        if ($request->deposit_step == 2) {
            $request->validate([
                'deposit_amount' => 'required',
				'deposit_method' => 'required',
				'deposit_method_id' => 'required'
            ]);

            // Set the deposit amount
            $deposit_amount = $request->deposit_amount;

            // Save the deposit data in the session for future steps
             $deposit_method = json_decode($request->deposit_method);
             $deposit_method_id = $request->deposit_method_id;

            $ticker = $deposit_method->wallet_ticker;
            if (!$ticker) {
                return response()->json(['message' => 'Invalid deposit method.'], 422);
            }

            // Check if wallet already exists for the selected ticker
            $wallet = BitgoWallets::where('user_id', Auth::id())
                ->where('coin_ticker', strtolower($ticker))
                ->first();

            // var_dump($wallet); exit;

            if (!$wallet) {
                // Generate a new wallet address if not found
                $bitgo = new BitgoController();
                $wallet = $bitgo->generateWalletAddress($ticker);
                if (isset($wallet['error'])) {
                    return response()->json(['message' => $wallet['error']], 422);
                }
            }

            // Pass the deposit amount to step 3 view
            return view('deposits.partials.step-3', compact('wallet', 'deposit_method', 'deposit_amount'));
        }

        if ($request->deposit_step == 3) {
            return view('deposits.partials.step-4', compact('deposit_method'));
        }

        // Process the deposit after confirmation (step 4)
        $wallet = Wallet::findOrFail($request->wallet_id);

        // Calculate bonus (example: 5% bonus for deposits over 1000)
        $bonus = $request->deposit_amount >= get_option('min_deposit_for_bonus') ? $request->deposit_amount * get_option('deposit_bonus', 0) : 0;

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
            $user->deposit($request->deposit_amount, ["description" => "wallet deposit"]);

            if ($bonus > 0) {
                $user->deposit($bonus, ["description" => "Wallet deposit bonus"]);
            }

            $deposit->update(['deposit_status' => 'completed']);

            Flasher::addSuccess('Deposit processed successfully!');
            return redirect()->route('deposits.show', $deposit->id);
        } catch (\Exception $e) {
            $deposit->update(['deposit_status' => 'failed']);
            Flasher::addError('Deposit processing failed. Please try again.');
            return back()->with('error', "Deposit processing failed. Please try again.");
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
            'completed_deposits' => Deposit::where('user_id', Auth::id())
                ->where('deposit_status', 'completed')
                ->count(),
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
