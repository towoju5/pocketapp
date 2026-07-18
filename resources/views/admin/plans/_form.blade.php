@php $editing = isset($plan); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Name</label>
        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Amount Type</label>
        <select name="amount_type" class="brand-input-dark" required>
            <option value="fixed" {{ old('amount_type', $plan->amount_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="range" {{ old('amount_type', $plan->amount_type ?? 'range') === 'range' ? 'selected' : '' }}>Range</option>
        </select>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Wallet Slug</label>
        <input type="text" name="wallet_slug" value="{{ old('wallet_slug', $plan->wallet_slug ?? 'qt_real_usd') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Fixed Amount</label>
        <input type="number" step="0.01" name="fixed_amount" value="{{ old('fixed_amount', $plan->fixed_amount ?? '') }}" class="brand-input-dark">
    </div>
    <div></div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Min Amount</label>
        <input type="number" step="0.01" name="min_amount" value="{{ old('min_amount', $plan->min_amount ?? '') }}" class="brand-input-dark">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Max Amount</label>
        <input type="number" step="0.01" name="max_amount" value="{{ old('max_amount', $plan->max_amount ?? '') }}" class="brand-input-dark">
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">ROI Percentage (total)</label>
        <input type="number" step="0.01" name="roi_percentage" value="{{ old('roi_percentage', $plan->roi_percentage ?? '') }}" class="brand-input-dark" required>
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Duration (days)</label>
        <input type="number" name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Daily Task Limit</label>
        <input type="number" name="daily_task_limit" value="{{ old('daily_task_limit', $plan->daily_task_limit ?? '') }}" class="brand-input-dark">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Max Reinvest Count</label>
        <input type="number" name="max_reinvest_count" value="{{ old('max_reinvest_count', $plan->max_reinvest_count ?? '') }}" class="brand-input-dark">
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Fee Discount %</label>
        <input type="number" step="0.01" name="fee_discount_percentage" value="{{ old('fee_discount_percentage', $plan->fee_discount_percentage ?? 0) }}" class="brand-input-dark">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Badge Color</label>
        <input type="text" name="badge_color" value="{{ old('badge_color', $plan->badge_color ?? '#3b82f6') }}" class="brand-input-dark">
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}" class="brand-input-dark">
    </div>

    <div class="flex items-end gap-6 pb-2">
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="capital_lock" value="1" {{ old('capital_lock', $plan->capital_lock ?? true) ? 'checked' : '' }} class="rounded border-slate-600 bg-transparent text-brand-blue">
            Capital Locked
        </label>
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-600 bg-transparent text-brand-blue">
            Active
        </label>
    </div>
</div>

<div class="mt-8 flex justify-end">
    <button type="submit" class="brand-btn-primary">{{ $editing ? 'Update Plan' : 'Create Plan' }}</button>
</div>
