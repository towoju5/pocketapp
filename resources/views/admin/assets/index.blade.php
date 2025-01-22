@extends('layouts.app')

@section('title', 'Assets Management')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="py-6">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h4 class="text-2xl font-semibold mb-6">Assets</h4>

                <!-- Filter Section -->
                <form action="{{ route('admin.assets.index') }}" method="GET" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <select name="asset_group" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Asset Groups</option>
                            @foreach($assetGroups as $group)
                                <option value="{{ $group }}" {{ $group == $assetGroup ? 'selected' : '' }}>
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Filter</button>
                    </div>
                </form>

                <div class="text-right mb-4">
                    <a href="{{ route('admin.assets.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add New Asset</a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <button type="button" class="absolute top-0 right-0 px-4 py-3" data-bs-dismiss="alert">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Symbol</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Asset Group</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Float Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Float</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($assets as $asset)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->symbol }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->asset_group }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($asset->exchange_float_type) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $asset->exchange_float }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <a href="{{ route('admin.assets.show', $asset) }}" class="inline-block bg-blue-500 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-600">View</a>
                                        <a href="{{ route('admin.assets.edit', $asset) }}" class="inline-block bg-yellow-500 text-white px-3 py-1 rounded-md text-sm hover:bg-yellow-600">Edit</a>
                                        <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center">No assets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex justify-center">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
