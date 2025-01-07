@extends('layouts.app')

@section('title', 'Edit Asset')
@section('content')
<div class="row gx-3">
    <div class="col-xxl-12">
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="mb-4">Edit Asset</h4>

                <form action="{{ route('admin.assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="symbol" class="form-label">Symbol</label>
                        <input type="text" name="symbol" id="symbol" class="form-control" placeholder="Enter asset symbol" value="{{ old('symbol', $asset->symbol) }}">
                        @error('symbol')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter asset name" value="{{ old('name', $asset->name) }}">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="asset_group" class="form-label">Asset Group</label>
                        <input type="text" name="asset_group" id="asset_group" class="form-control" placeholder="Enter asset group" value="{{ old('asset_group', $asset->asset_group) }}">
                        @error('asset_group')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exchange_float_type" class="form-label">Exchange Float Type</label>
                        <select name="exchange_float_type" id="exchange_float_type" class="form-control">
                            <option value="fixed" {{ old('exchange_float_type', $asset->exchange_float_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="float" {{ old('exchange_float_type', $asset->exchange_float_type) == 'float' ? 'selected' : '' }}>Float</option>
                            <option value="combine" {{ old('exchange_float_type', $asset->exchange_float_type) == 'combine' ? 'selected' : '' }}>Combine</option>
                        </select>
                        @error('exchange_float_type')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="exchange_float" class="form-label">Exchange Float</label>
                        <input type="text" name="exchange_float" id="exchange_float" class="form-control" placeholder="Enter exchange float" value="{{ old('exchange_float', $asset->exchange_float) }}">
                        @error('exchange_float')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
