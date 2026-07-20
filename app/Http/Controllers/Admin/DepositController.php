<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $deposits = Deposit::with('user')
            ->when($request->status, fn ($q) => $q->where('deposit_status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.deposits.index', compact('deposits'));
    }

    public function show(Deposit $deposit)
    {
        return view('admin.deposits.show', compact('deposit'));
    }
}
