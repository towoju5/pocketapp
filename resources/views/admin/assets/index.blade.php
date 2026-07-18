@extends('layouts.admin.app')

@section('title', 'Assets')

@section('content')
    <x-page-header title="Assets" subtitle="Tradeable instruments available on the platform.">
        <x-slot:actions>
            <a href="{{ route('admin.assets.create') }}" class="brand-btn-primary">Add New Asset</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card padding="p-6">
        <form action="{{ route('admin.assets.index') }}" method="GET" class="mb-6 flex flex-wrap gap-3">
            <select name="asset_group" class="brand-input-dark max-w-xs">
                <option value="">All Asset Groups</option>
                @foreach ($assetGroups as $group)
                    <option value="{{ $group }}" {{ $group == $assetGroup ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
            <button type="submit" class="brand-btn-outline">Filter</button>
        </form>

        <x-data-table>
            <thead>
                <tr><th>#</th><th>Symbol</th><th>Name</th><th>Group</th><th>Float</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($assets as $asset)
                    <tr>
                        <td>{{ $asset->id }}</td>
                        <td class="font-semibold text-white">{{ $asset->symbol }}</td>
                        <td>{{ $asset->name }}</td>
                        <td>{{ $asset->asset_group }}</td>
                        <td>{{ $asset->exchange_float }}</td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.assets.show', $asset) }}" class="text-brand-blue hover:underline">View</a>
                            <a href="{{ route('admin.assets.edit', $asset) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No assets found.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $assets->links() }}</div>
    </x-glass-card>
@endsection
