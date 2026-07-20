<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::withCount('redemptions')->latest()->paginate(10);

        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function create()
    {
        return view('admin.promo-codes.create');
    }

    private function rules(): array
    {
        return [
            'promo_title' => 'required|string|max:255',
            'promo_code' => 'required|string|max:50|unique:promo_codes,promo_code',
            'promo_discount' => 'required|numeric|min:0',
            'promo_discount_type' => 'required|in:flat,percentage',
            'promo_start_date_time' => 'required|date',
            'promo_ends_date_time' => 'required|date|after:promo_start_date_time',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        $validated['promo_created_by'] = auth()->id();

        PromoCode::create($validated);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code created successfully.');
    }

    public function edit(PromoCode $promoCode)
    {
        return view('admin.promo-codes.edit', compact('promoCode'));
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $rules = $this->rules();
        $rules['promo_code'] = 'required|string|max:50|unique:promo_codes,promo_code,' . $promoCode->id;

        $validated = $request->validate($rules);
        $promoCode->update($validated);

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code updated successfully.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return redirect()->route('admin.promo-codes.index')->with('success', 'Promo code deleted.');
    }
}
