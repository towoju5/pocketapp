<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function tradeList()
    {
        $trades = Trade::latest()->get();
        // return response()->json($trades);
        return view('mini-pages.trade-list', compact('trades'));
    }
}
