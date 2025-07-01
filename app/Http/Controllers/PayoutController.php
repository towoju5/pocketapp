<?php
namespace App\Http\Controllers;

use App\Models\Bitgo;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = Payout::whereUserId(auth()->id())->latest()->get();
        return view('payout.index', compact('payouts'));
    }

    public function create()
    {
        $methods = Bitgo::where('can_payout', true)->get();
        return view('payout.create', compact('methods'));
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'amount'         => 'required|numeric|min:1',
                'payment_method' => 'required|string',
                'address'        => 'required|string|max:255', // limit address length
            ]);

            if ($validate->fails()) {
                return back()->withErrors($validate)->withInput();
            }

            $user = auth()->user();
            if(!$user) {
                session()->logout();
                return back()->with('error', 'Please re-login to proceed');
            }

            if (!debit_user($user->active_wallet_slug, $request->amount, "Customer Payout")) {
                return back()->with('error', 'Insufficient balance in your account.')->withInput();
            }

            $payout = Payout::create([
                'user_id'           => auth()->id(),
                'payout_amount'     => $request->amount,
                'payout_date_time'  => now(),
                'payout_status'     => 'pending', // or 'completed'?
                'payout_method'     => $request->payment_method,
                'payout_bonus'      => $request->address,
                'payout_extra_info' => $request->only(['amount', 'payment_method', 'address']),
            ]);

            // dd($payout); 

            return back()->with('success', 'Payout request submitted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'An error occurred: ' . $th->getMessage());
        }
    }

    public function show(Payout $payout)
    {
        return view('payout.show', compact('payout'));
    }

    public function swapBalance()
    {
        //
    }
}
