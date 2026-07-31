<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\PaymentProvider;
use App\Models\User;
// use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $gatewayProviders = PaymentProvider::where('is_active', true)->where('can_deposit', true)->orderBy('sort_order')->get();
        return view('deposits.create', compact('wallets', 'gatewayProviders'));
    }

    /**
     * Same two-step interaction as the old BitGo wizard (pick a method,
     * then an amount) — step 1 here just resolves to a gateway instead of a
     * Bitgo row. Unlike the old wizard, nothing here credits a wallet: step
     * 2's "Continue" submits straight to GatewayCheckoutController, which
     * redirects to the gateway's real hosted checkout, and only that
     * gateway's webhook ever credits the deposit once payment is confirmed.
     */
    public function store(Request $request)
    {
        $request->validate([
            'deposit_step' => 'required|in:1',
            'deposit_method' => 'required|string',
        ]);

        $provider = PaymentProvider::where('slug', $request->deposit_method)
            ->where('is_active', true)
            ->where('can_deposit', true)
            ->first();

        if (!$provider) {
            return response()->json(['message' => 'Invalid or unavailable payment method.'], 422);
        }

        return view('deposits.partials.step-2', compact('provider'));
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
