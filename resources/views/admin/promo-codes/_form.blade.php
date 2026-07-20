@php
    $editing = isset($promoCode);
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Title</label>
        <input type="text" name="promo_title" value="{{ old('promo_title', $promoCode->promo_title ?? '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Code</label>
        <input type="text" name="promo_code" value="{{ old('promo_code', $promoCode->promo_code ?? '') }}" class="brand-input-dark font-mono" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Discount Type</label>
        <select name="promo_discount_type" class="brand-input-dark" required>
            <option value="flat" {{ old('promo_discount_type', $promoCode->promo_discount_type ?? '') == 'flat' ? 'selected' : '' }}>Flat amount ($)</option>
            <option value="percentage" {{ old('promo_discount_type', $promoCode->promo_discount_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage of last deposit (%)</option>
        </select>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Discount Value</label>
        <input type="number" step="0.01" name="promo_discount" value="{{ old('promo_discount', $promoCode->promo_discount ?? '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Valid From</label>
        <input type="datetime-local" name="promo_start_date_time" value="{{ old('promo_start_date_time', isset($promoCode) ? \Illuminate\Support\Carbon::parse($promoCode->promo_start_date_time)->format('Y-m-d\TH:i') : '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Valid Until</label>
        <input type="datetime-local" name="promo_ends_date_time" value="{{ old('promo_ends_date_time', isset($promoCode) ? \Illuminate\Support\Carbon::parse($promoCode->promo_ends_date_time)->format('Y-m-d\TH:i') : '') }}" class="brand-input-dark" required>
    </div>
</div>

<div class="mt-8">
    <button type="submit" class="brand-btn-primary">{{ $editing ? 'Update' : 'Save' }}</button>
</div>
