<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use Illuminate\Http\Request;

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
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required|string',
            // 'account_
        ]);

        $payout = new Payout();
    }

    public function show(Payout $payout)
    {
        return view('payout.show', compact('payout'));
    }
    
}
