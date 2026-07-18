@extends('layouts.admin.app')

@section('title', 'Asset: ' . $asset->symbol)

@section('content')
    <x-page-header :title="$asset->symbol" subtitle="{{ $asset->name }}">
        <x-slot:actions>
            <a href="{{ route('admin.assets.edit', $asset) }}" class="brand-btn-outline">Edit</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div><dt class="text-slate-400">ID</dt><dd class="font-semibold text-white">{{ $asset->id }}</dd></div>
            <div><dt class="text-slate-400">Asset Group</dt><dd class="font-semibold text-white">{{ $asset->asset_group }}</dd></div>
            <div><dt class="text-slate-400">Exchange Float</dt><dd class="font-semibold text-white">{{ $asset->exchange_float }}</dd></div>
            <div><dt class="text-slate-400">Profit Margin</dt><dd class="font-semibold text-white">{{ $asset->asset_profit_margin }}</dd></div>
            <div><dt class="text-slate-400">Created</dt><dd class="font-semibold text-white">{{ $asset->created_at->format('d M, Y H:i') }}</dd></div>
            <div><dt class="text-slate-400">Updated</dt><dd class="font-semibold text-white">{{ $asset->updated_at->format('d M, Y H:i') }}</dd></div>
        </dl>

        <div class="mt-8 flex justify-end gap-3">
            <a href="{{ route('admin.assets.index') }}" class="brand-btn-outline">Back</a>
            <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                @csrf @method('DELETE')
                <button type="submit" class="brand-btn bg-brand-danger text-white hover:bg-red-600">Delete</button>
            </form>
        </div>
    </x-glass-card>
@endsection
