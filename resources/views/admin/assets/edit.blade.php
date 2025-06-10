@extends('layouts.admin.app')

@section('title', 'Edit Asset')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-light border-secondary">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Edit Asset</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.assets.update', $asset) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="symbol">Symbol</label>
                            <input type="text" class="form-control @error('symbol') is-invalid @enderror" name="symbol" id="symbol" value="{{ old('symbol', $asset->symbol) }}" placeholder="Enter asset symbol">
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $asset->name) }}" placeholder="Enter asset name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="asset_group">Asset Group</label>
                            <input type="text" class="form-control @error('asset_group') is-invalid @enderror" name="asset_group" id="asset_group" value="{{ old('asset_group', $asset->asset_group) }}" placeholder="Enter asset group">
                            @error('asset_group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exchange_float">Exchange Float</label>
                            <input type="text" class="form-control @error('exchange_float') is-invalid @enderror" name="exchange_float" id="exchange_float" value="{{ old('exchange_float', $asset->exchange_float) }}" placeholder="Enter exchange float">
                            @error('exchange_float')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="asset_profit_margin">Asset Profit Margin</label>
                            <input type="number" step="0.01" class="form-control @error('asset_profit_margin') is-invalid @enderror" name="asset_profit_margin" id="asset_profit_margin" value="{{ old('asset_profit_margin', $asset->asset_profit_margin) }}" placeholder="Enter profit margin">
                            @error('asset_profit_margin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="is_otc">Is OTC</label>
                            <select name="is_otc" id="is_otc" class="form-control @error('is_otc') is-invalid @enderror">
                                <option value="1" {{ old('is_otc', $asset->is_otc) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_otc', $asset->is_otc) ? '' : 'selected' }}>No</option>
                            </select>
                            @error('is_otc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="extra_data">Extra Data (JSON)</label>
                            <textarea name="extra_data" id="extra_data" rows="4" class="form-control @error('extra_data') is-invalid @enderror" placeholder="Enter extra data in JSON format">{{ old('extra_data', is_array($asset->extra_data) ? json_encode($asset->extra_data, JSON_PRETTY_PRINT) : $asset->extra_data) }}</textarea>
                            @error('extra_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
