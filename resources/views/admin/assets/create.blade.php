@extends('layouts.admin.app')

@section('title', 'Create Asset')

@section('content')
    <x-page-header title="Create New Asset" />

    <x-glass-card>
        <form action="{{ route('admin.assets.store') }}" method="POST" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @csrf

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Symbol</label>
                <input type="text" name="symbol" class="brand-input-dark" value="{{ old('symbol') }}" required>
                @error('symbol') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Name</label>
                <input type="text" name="name" class="brand-input-dark" value="{{ old('name') }}" required>
                @error('name') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Asset Group</label>
                <input type="text" name="asset_group" class="brand-input-dark" value="{{ old('asset_group') }}" required>
                @error('asset_group') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Exchange Float</label>
                <input type="text" name="exchange_float" class="brand-input-dark" value="{{ old('exchange_float') }}" required>
                @error('exchange_float') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Profit Margin</label>
                <input type="number" step="0.01" name="asset_profit_margin" class="brand-input-dark" value="{{ old('asset_profit_margin') }}">
                @error('asset_profit_margin') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end pb-2">
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="is_otc" value="1" {{ old('is_otc') ? 'checked' : '' }} class="rounded border-slate-600 bg-transparent text-brand-blue">
                    OTC Asset
                </label>
            </div>

            <div class="sm:col-span-2 flex gap-3">
                <button type="submit" class="brand-btn-primary">Create Asset</button>
                <a href="{{ route('admin.assets.index') }}" class="brand-btn-outline">Cancel</a>
            </div>
        </form>
    </x-glass-card>
@endsection
