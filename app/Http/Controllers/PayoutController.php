<?php
namespace App\Http\Controllers;

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
        return view('payout.create');
    }

    public function store(Request $request)
    {
        try {
            // $validate = Validator::make($request->all(), [
            //     'amount'         => 'required|numeric|min:1',
            //     'payment_method' => 'required|string',
            //     'address'        => 'required|string',
            // ]);

            // if ($validate->fails()) {
            //     var_dump(['error' => $validate]);
            //     return back()->withErrors($validate)->withInput();
            // }

            // $validated = $validate->validated();

            $payout = new Payout();

            if (! debit_user('qt_real_usd', $request['amount'], "Customer Payout")) {
                return back()->with('error', 'Insufficient balance in your account.')->withInput();
            }

            $payout->user_id           = auth()->id();
            $payout->payout_amount     = $request->amount;
            $payout->payout_date_time  = now();
            $payout->payout_status     = "completed";
            $payout->payout_method     = $request->payment_method;
            $payout->payout_bonus      = $request->address;
            $payout->payout_extra_info = $request->all();

            if ($payout->save()) {
                return back()->with('success', 'Payout request submitted successfully.');
            }
        } catch (\Throwable $th) {
            var_dump(['error' => $th->getMessage()]);
        }
    }

    public function show(Payout $payout)
    {
        return view('payout.show', compact('payout'));
    }
}
