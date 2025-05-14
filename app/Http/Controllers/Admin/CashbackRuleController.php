<?php

// app/Http/Controllers/Admin/CashbackRuleController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashbackRule;
use Illuminate\Http\Request;

class CashbackRuleController extends Controller
{
    public function index()
    {
        $rules = CashbackRule::latest()->paginate(10);
        return view('admin.cashbacks.index', compact('rules'));
    }

    public function create()
    {
        return view('admin.cashbacks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:loss,volume,promo',
            'percentage' => 'required|numeric|min:0|max:100',
            'volume_threshold' => 'nullable|integer|min:0',
            'promo_code' => 'nullable|string|max:50',
            'active' => 'boolean',
        ]);

        CashbackRule::create($request->all());

        return redirect()->route('admin.cashbacks.index')->with('success', 'Cashback rule created successfully.');
    }

    public function edit(CashbackRule $cashbackRule)
    {
        return view('admin.cashbacks.edit', compact('cashbackRule'));
    }

    public function update(Request $request, CashbackRule $cashbackRule)
    {
        $request->validate([
            'type' => 'required|in:loss,volume,promo',
            'percentage' => 'required|numeric|min:0|max:100',
            'volume_threshold' => 'nullable|integer|min:0',
            'promo_code' => 'nullable|string|max:50',
            'active' => 'boolean',
        ]);

        $cashbackRule->update($request->all());

        return redirect()->route('admin.cashbacks.index')->with('success', 'Cashback rule updated successfully.');
    }

    public function destroy(CashbackRule $cashbackRule)
    {
        $cashbackRule->delete();
        return redirect()->route('admin.cashbacks.index')->with('success', 'Cashback rule deleted.');
    }
}
