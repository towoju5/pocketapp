<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = Trade::with('user')
            ->when($request->status, fn ($q) => $q->where('trade_status', $request->status))
            ->when($request->mode, fn ($q) => $q->where('trade_wallet', 'like', "%{$request->mode}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.trades.index', compact('trades'));
    }

    public function show(Trade $trade)
    {
        return view('admin.trades.show', compact('trade'));
    }
}
