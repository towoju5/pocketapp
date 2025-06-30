<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bitgo;
use Illuminate\Http\Request;

class BitgoController extends Controller
{
    public function index()
    {
        $wallets = Bitgo::latest()->paginate(10);
        return view('admin.bitgo.index', compact('wallets'));
    }

    public function create()
    {
        return view('admin.bitgo.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wallet_id'     => 'required|string|max:255',
            'wallet_name'   => 'required|string|max:255',
            'wallet_ticker' => 'required|string|max:50',
            'type'          => 'required|string|max:50',
            'require_memo'  => 'required|boolean',
            'can_deposit'   => 'required|boolean',
            'can_payout'    => 'required|boolean',
            'coin_logo'     => 'nullable|url',
            'meta_data'     => 'nullable',
        ]);

        Bitgo::create($validated);

        return redirect()->route('admin.bitgo.index')->with('success', 'Wallet created.');
    }

    public function edit(Bitgo $bitgo)
    {
        return view('admin.bitgo.edit', compact('bitgo'));
    }

    public function update(Request $request, Bitgo $bitgo)
    {
        $validated = $request->validate([
            'wallet_id'     => 'required|string|max:255',
            'wallet_name'   => 'required|string|max:255',
            'wallet_ticker' => 'required|string|max:50',
            'type'          => 'required|string|max:50',
            'require_memo'  => 'required|boolean',
            'can_deposit'   => 'required|boolean',
            'can_payout'    => 'required|boolean',
            'coin_logo'     => 'nullable|url',
            'meta_data'     => 'nullable',
        ]);

        $bitgo->update($validated);

        return redirect()->route('admin.bitgo.index')->with('success', 'Wallet updated.');
    }

    public function destroy(Bitgo $bitgo)
    {
        $bitgo->delete();
        return redirect()->route('admin.bitgo.index')->with('success', 'Wallet deleted.');
    }
}
