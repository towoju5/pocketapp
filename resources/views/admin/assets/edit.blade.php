@extends('layouts.admin.app')

@section('title', 'Edit Asset')

@section('content')
    <x-page-header :title="'Edit: ' . $asset->symbol" />

    <x-glass-card>
        <form action="{{ route('admin.assets.update', $asset) }}" method="POST" class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Symbol</label>
                <input type="text" name="symbol" class="brand-input-dark" value="{{ old('symbol', $asset->symbol) }}">
                @error('symbol') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Name</label>
                <input type="text" name="name" class="brand-input-dark" value="{{ old('name', $asset->name) }}">
                @error('name') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Asset Group</label>
                <input type="text" name="asset_group" class="brand-input-dark" value="{{ old('asset_group', $asset->asset_group) }}">
                @error('asset_group') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Exchange Float</label>
                <input type="text" name="exchange_float" class="brand-input-dark" value="{{ old('exchange_float', $asset->exchange_float) }}">
                @error('exchange_float') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Profit Margin</label>
                <input type="number" step="0.01" name="asset_profit_margin" class="brand-input-dark" value="{{ old('asset_profit_margin', $asset->asset_profit_margin) }}">
                @error('asset_profit_margin') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Is OTC</label>
                <select name="is_otc" class="brand-input-dark">
                    <option value="1" {{ old('is_otc', $asset->is_otc) ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('is_otc', $asset->is_otc) ? '' : 'selected' }}>No</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Extra Data (JSON)</label>
                <textarea name="extra_data" rows="4" class="brand-input-dark">{{ old('extra_data', is_array($asset->extra_data) ? json_encode($asset->extra_data, JSON_PRETTY_PRINT) : $asset->extra_data) }}</textarea>
                @error('extra_data') <p class="mt-1 text-xs text-brand-danger">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2 flex gap-3">
                <button type="submit" class="brand-btn-primary">Update</button>
                <a href="{{ route('admin.assets.index') }}" class="brand-btn-outline">Cancel</a>
            </div>
        </form>
    </x-glass-card>
@endsection
