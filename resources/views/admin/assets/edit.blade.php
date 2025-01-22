@extends('layouts.app')

@section('title', 'Edit Asset')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h4 class="text-2xl font-semibold mb-6">Edit Asset</h4>

            <form action="{{ route('admin.assets.update', $asset) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="symbol" class="block text-sm font-medium text-gray-700">Symbol</label>
                        <input type="text" name="symbol" id="symbol" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter asset symbol" value="{{ old('symbol', $asset->symbol) }}">
                        @error('symbol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter asset name" value="{{ old('name', $asset->name) }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_symbol" class="block text-sm font-medium text-gray-700">Display Symbol</label>
                        <input type="text" name="display_symbol" id="display_symbol" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter display symbol" value="{{ old('display_symbol', $asset->display_symbol) }}">
                        @error('display_symbol')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asset_group" class="block text-sm font-medium text-gray-700">Asset Group</label>
                        <input type="text" name="asset_group" id="asset_group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter asset group" value="{{ old('asset_group', $asset->asset_group) }}">
                        @error('asset_group')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="yahoo_ticker" class="block text-sm font-medium text-gray-700">Yahoo Ticker</label>
                        <input type="text" name="yahoo_ticker" id="yahoo_ticker" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter Yahoo ticker" value="{{ old('yahoo_ticker', $asset->yahoo_ticker) }}">
                        @error('yahoo_ticker')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="olymptrade_ticker" class="block text-sm font-medium text-gray-700">Olymptrade Ticker</label>
                        <input type="text" name="olymptrade_ticker" id="olymptrade_ticker" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter Olymptrade ticker" value="{{ old('olymptrade_ticker', $asset->olymptrade_ticker) }}">
                        @error('olymptrade_ticker')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="exchange_float_type" class="block text-sm font-medium text-gray-700">Exchange Float Type</label>
                        <select name="exchange_float_type" id="exchange_float_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="fixed" {{ old('exchange_float_type', $asset->exchange_float_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="float" {{ old('exchange_float_type', $asset->exchange_float_type) == 'float' ? 'selected' : '' }}>Float</option>
                            <option value="combine" {{ old('exchange_float_type', $asset->exchange_float_type) == 'combine' ? 'selected' : '' }}>Combine</option>
                        </select>
                        @error('exchange_float_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="exchange_float" class="block text-sm font-medium text-gray-700">Exchange Float</label>
                        <input type="text" name="exchange_float" id="exchange_float" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter exchange float" value="{{ old('exchange_float', $asset->exchange_float) }}">
                        @error('exchange_float')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Update</button>
                        <a href="{{ route('admin.assets.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
