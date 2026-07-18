@php $editing = isset($task); @endphp

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Title</label>
        <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}" class="brand-input-dark" required>
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Description</label>
        <textarea name="description" rows="3" class="brand-input-dark">{{ old('description', $task->description ?? '') }}</textarea>
    </div>

    <div class="sm:col-span-2">
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">External URL</label>
        <input type="url" name="external_url" value="{{ old('external_url', $task->external_url ?? '') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Reward Amount</label>
        <input type="number" step="0.01" name="reward_amount" value="{{ old('reward_amount', $task->reward_amount ?? '') }}" class="brand-input-dark" required>
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Wallet Slug</label>
        <input type="text" name="wallet_slug" value="{{ old('wallet_slug', $task->wallet_slug ?? 'qt_real_usd') }}" class="brand-input-dark" required>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Daily Limit (per user)</label>
        <input type="number" name="daily_limit" value="{{ old('daily_limit', $task->daily_limit ?? 1) }}" class="brand-input-dark" required>
    </div>
    <div class="flex items-end pb-2">
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $task->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-600 bg-transparent text-brand-blue">
            Active
        </label>
    </div>
</div>

<div class="mt-8 flex justify-end">
    <button type="submit" class="brand-btn-primary">{{ $editing ? 'Update Task' : 'Create Task' }}</button>
</div>
