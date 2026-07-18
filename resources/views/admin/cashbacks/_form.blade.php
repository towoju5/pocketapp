{{-- resources/views/admin/cashbacks/_form.blade.php --}}
@php
    $editing = isset($cashbackRule);
@endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Type</label>
        <select name="type" class="brand-input-dark" required>
            <option value="loss" {{ old('type', $cashbackRule->type ?? '') == 'loss' ? 'selected' : '' }}>Loss</option>
            <option value="volume" {{ old('type', $cashbackRule->type ?? '') == 'volume' ? 'selected' : '' }}>Volume</option>
            <option value="promo" {{ old('type', $cashbackRule->type ?? '') == 'promo' ? 'selected' : '' }}>Promo</option>
        </select>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Percentage (%)</label>
        <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $cashbackRule->percentage ?? '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Volume Threshold (for volume type)</label>
        <input type="number" name="volume_threshold" value="{{ old('volume_threshold', $cashbackRule->volume_threshold ?? '') }}" class="brand-input-dark">
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Promo Code (for promo type)</label>
        <input type="text" name="promo_code" value="{{ old('promo_code', $cashbackRule->promo_code ?? '') }}" class="brand-input-dark">
    </div>

    <div class="flex items-end pb-2">
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="active" value="1" {{ old('active', $cashbackRule->active ?? true) ? 'checked' : '' }} class="rounded border-slate-600 bg-transparent text-brand-blue">
            Active
        </label>
    </div>
</div>

<div class="mt-8">
    <button type="submit" class="brand-btn-primary">{{ $editing ? 'Update' : 'Save' }}</button>
</div>
