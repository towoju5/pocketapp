@extends('layouts.admin.app')

@section('title', 'Create Signal')

@section('content')
    <x-page-header title="Create Signal" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.signals.store') }}" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @csrf

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Asset</label>
                <select name="asset" class="brand-input-dark" required>
                    @foreach ($assets as $asset)
                        <option value="{{ $asset->symbol }}">{{ $asset->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Amount</label>
                <input type="number" step="0.01" name="amount" class="brand-input-dark" required>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Direction</label>
                <select name="direction" class="brand-input-dark" required>
                    <option value="up">Up</option>
                    <option value="down">Down</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Duration (seconds)</label>
                <input type="number" name="duration" class="brand-input-dark" required>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Expected Profit (optional)</label>
                <input type="number" step="0.01" name="expected_profit" class="brand-input-dark">
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Start Price (optional)</label>
                <input type="number" step="0.000001" name="start_price" class="brand-input-dark">
            </div>

            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Notes</label>
                <textarea name="notes" rows="3" class="brand-input-dark"></textarea>
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="brand-btn-primary">Create Signal</button>
            </div>
        </form>
    </x-glass-card>
@endsection
