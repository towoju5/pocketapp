@php $editing = isset($rate); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Level</label>
        <select name="level" class="brand-input-dark" required>
            @foreach ([1, 2, 3] as $lvl)
                <option value="{{ $lvl }}" {{ old('level', $rate->level ?? '') == $lvl ? 'selected' : '' }}>Level {{ $lvl }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Activity Type</label>
        <select name="activity_type" class="brand-input-dark" required>
            <option value="trade" {{ old('activity_type', $rate->activity_type ?? '') == 'trade' ? 'selected' : '' }}>Trade</option>
            <option value="plan" {{ old('activity_type', $rate->activity_type ?? '') == 'plan' ? 'selected' : '' }}>Plan</option>
        </select>
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Percentage</label>
        <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $rate->percentage ?? '') }}" class="brand-input-dark" required>
    </div>

    <div class="flex items-end pb-2">
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $rate->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-600 bg-transparent text-brand-blue">
            Active
        </label>
    </div>
</div>

<div class="mt-8 flex justify-end">
    <button type="submit" class="brand-btn-primary">{{ $editing ? 'Update Rate' : 'Create Rate' }}</button>
</div>
