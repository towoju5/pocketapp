<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function tradeList()
    {
        return $trades = Trade::where(['user_id' => auth()->id(), 'trade_status' => 'pending'])->latest()->get();
        // return response()->json($trades);
        return view('mini-pages.trade-list', compact('trades'));
    }

}
