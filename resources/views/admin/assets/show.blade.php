@extends('layouts.admin.app')

@section('title', 'View Asset')
@section('content')
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="mb-4">Asset Details</h4>

                    <table class="table table-striped">
                        <tr>
                            <th>ID</th>
                            <td>{{ $asset->id }}</td>
                        </tr>
                        <tr>
                            <th>Symbol</th>
                            <td>{{ $asset->symbol }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $asset->name }}</td>
                        </tr>
                        <tr>
                            <th>Asset Group</th>
                            <td>{{ $asset->asset_group }}</td>
                        </tr>
                        <tr>
                            <th>Exchange Float Type</th>
                            <td>{{ ucfirst($asset->exchange_float_type) }}</td>
                        </tr>
                        <tr>
                            <th>Exchange Float</th>
                            <td>{{ $asset->exchange_float }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $asset->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $asset->updated_at }}</td>
                        </tr>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.assets.edit', $asset) }}" class="btn btn-warning me-2">Edit</a>
                        <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
