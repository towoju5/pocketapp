<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpressTrade;
use Illuminate\Http\Request;

class ExpressTradeController extends Controller
{
    public function index(Request $request)
    {
        $trades = ExpressTrade::with(['user', 'asset'])
            ->when($request->status, fn ($q) => $q->where('trade_status', $request->status))
            ->when($request->mode, fn ($q) => $q->where('trade_wallet', 'like', "%{$request->mode}%"))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.express-trades.index', compact('trades'));
    }

    public function show(ExpressTrade $expressTrade)
    {
        return view('admin.express-trades.show', ['trade' => $expressTrade]);
    }
}
