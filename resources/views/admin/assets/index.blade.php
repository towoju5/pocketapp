@extends('layouts.admin.app')

@section('title', 'Assets Management')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Assets</h4>

            <!-- Filter Section -->
            <form action="{{ route('admin.assets.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <select name="asset_group" class="form-select">
                            <option value="">All Asset Groups</option>
                            @foreach($assetGroups as $group)
                                <option value="{{ $group }}" {{ $group == $assetGroup ? 'selected' : '' }}>
                                    {{ $group }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <div class="text-end mb-3">
                <a href="{{ route('admin.assets.create') }}" class="btn btn-primary">Add New Asset</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Symbol</th>
                            <th>Name</th>
                            <th>Asset Group</th>
                            <th>Is_Active</th>
                            <th>Is_Float</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>{{ $asset->id }}</td>
                                <td>{{ $asset->symbol }}</td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ $asset->asset_group }}</td>
                                <td>{{ $asset->is_active }}</td>
                                <td>{{ $asset->is_float }}</td>
                                <td>
                                    <a href="{{ route('admin.assets.show', $asset) }}" class="btn btn-sm btn-info text-white">View</a>
                                    <a href="{{ route('admin.assets.edit', $asset) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                                    <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No assets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $assets->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
