@extends('layouts.app')

@section('title', 'Assets Management')
@section('content')
<div class="row gx-3">
    <div class="col-xxl-12">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-4">Assets</h4>

                <!-- Filter Section -->
                <form action="{{ route('admin.assets.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <select name="asset_group" class="form-control">
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

                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Symbol</th>
                            <th>Name</th>
                            <th>Asset Group</th>
                            <th>Float Type</th>
                            <th>Float</th>
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
                                <td>{{ ucfirst($asset->exchange_float_type) }}</td>
                                <td>{{ $asset->exchange_float }}</td>
                                <td>
                                    <a href="{{ route('admin.assets.show', $asset) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('admin.assets.edit', $asset) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $assets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
