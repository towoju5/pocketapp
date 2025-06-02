@extends('layouts.admin.app')

@section('title', 'Create Asset')
@section('content')
<div class="container py-5">
    <div class="mx-auto text-dark rounded shadow border border-secondary">
        <div class="card">
            <div class="card-body">
                <div class="p-4">
                    <h4 class="mb-4">Create New Asset</h4>

                    <form action="{{ route('admin.assets.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="symbol" class="form-label">Symbol</label>
                            <input type="text" name="symbol" id="symbol" class="form-control border-secondary" placeholder="Enter asset symbol" value="{{ old('symbol') }}">
                            @error('symbol')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control border-secondary" placeholder="Enter asset name" value="{{ old('name') }}">
                            @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="display_symbol" class="form-label">Display Symbol</label>
                            <input type="text" name="display_symbol" id="display_symbol" class="form-control border-secondary" placeholder="Enter display symbol" value="{{ old('display_symbol') }}">
                            @error('display_symbol')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="asset_group" class="form-label">Asset Group</label>
                            <input type="text" name="asset_group" id="asset_group" class="form-control border-secondary" placeholder="Enter asset group" value="{{ old('asset_group') }}">
                            @error('asset_group')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="yahoo_ticker" class="form-label">Yahoo Ticker</label>
                            <input type="text" name="yahoo_ticker" id="yahoo_ticker" class="form-control border-secondary" placeholder="Enter Yahoo ticker" value="{{ old('yahoo_ticker') }}">
                            @error('yahoo_ticker')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="olymptrade_ticker" class="form-label">Olymptrade Ticker</label>
                            <input type="text" name="olymptrade_ticker" id="olymptrade_ticker" class="form-control border-secondary" placeholder="Enter Olymptrade ticker" value="{{ old('olymptrade_ticker') }}">
                            @error('olymptrade_ticker')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exchange_float_type" class="form-label">Exchange Float Type</label>
                            <select name="exchange_float_type" id="exchange_float_type" class="form-select border-secondary">
                                <option value="fixed">Fixed</option>
                                <option value="float">Float</option>
                                <option value="combine">Combine</option>
                            </select>
                            @error('exchange_float_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="exchange_float" class="form-label">Exchange Float</label>
                            <input type="text" name="exchange_float" id="exchange_float" class="form-control border-secondary" placeholder="Enter exchange float" value="{{ old('exchange_float') }}">
                            @error('exchange_float')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary">Create Asset</button>
                            <a href="{{ route('admin.assets.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection