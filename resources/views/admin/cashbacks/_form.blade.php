{{-- resources/views/admin/cashbacks/_form.blade.php --}}
@php
    $editing = isset($cashbackRule);
@endphp

<div class="mb-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-control" required>
        <option value="loss" {{ old('type', $cashbackRule->type ?? '') == 'loss' ? 'selected' : '' }}>Loss</option>
        <option value="volume" {{ old('type', $cashbackRule->type ?? '') == 'volume' ? 'selected' : '' }}>Volume</option>
        <option value="promo" {{ old('type', $cashbackRule->type ?? '') == 'promo' ? 'selected' : '' }}>Promo</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Percentage (%)</label>
    <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $cashbackRule->percentage ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Volume Threshold (for volume type)</label>
    <input type="number" name="volume_threshold" value="{{ old('volume_threshold', $cashbackRule->volume_threshold ?? '') }}" class="form-control">
</div>

<div class="mb-3">
    <label class="form-label">Promo Code (for promo type)</label>
    <input type="text" name="promo_code" value="{{ old('promo_code', $cashbackRule->promo_code ?? '') }}" class="form-control">
</div>

<div class="form-check mb-3">
    <input type="checkbox" name="active" value="1" {{ old('active', $cashbackRule->active ?? true) ? 'checked' : '' }} class="form-check-input">
    <label class="form-check-label">Active</label>
</div>
