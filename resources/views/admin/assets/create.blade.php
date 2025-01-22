@extends('layouts.app')

@section('title', 'Create Asset')
@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto bg-[#1f2334] rounded-lg shadow-lg border border-[#293341]">
        <div class="p-6">
            <h4 class="text-2xl font-semibold mb-6 text-white">Create New Asset</h4>

            <form action="{{ route('admin.assets.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="symbol" class="block text-sm font-medium text-gray-200">Symbol</label>
                        <input type="text" name="symbol" id="symbol" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter asset symbol" value="{{ old('symbol') }}">
                        @error('symbol')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-200">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter asset name" value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_symbol" class="block text-sm font-medium text-gray-200">Display Symbol</label>
                        <input type="text" name="display_symbol" id="display_symbol" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter display symbol" value="{{ old('display_symbol') }}">
                        @error('display_symbol')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asset_group" class="block text-sm font-medium text-gray-200">Asset Group</label>
                        <input type="text" name="asset_group" id="asset_group" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter asset group" value="{{ old('asset_group') }}">
                        @error('asset_group')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="yahoo_ticker" class="block text-sm font-medium text-gray-200">Yahoo Ticker</label>
                        <input type="text" name="yahoo_ticker" id="yahoo_ticker" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter Yahoo ticker" value="{{ old('yahoo_ticker') }}">
                        @error('yahoo_ticker')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="olymptrade_ticker" class="block text-sm font-medium text-gray-200">Olymptrade Ticker</label>
                        <input type="text" name="olymptrade_ticker" id="olymptrade_ticker" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter Olymptrade ticker" value="{{ old('olymptrade_ticker') }}">
                        @error('olymptrade_ticker')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="exchange_float_type" class="block text-sm font-medium text-gray-200">Exchange Float Type</label>
                        <select name="exchange_float_type" id="exchange_float_type" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="fixed">Fixed</option>
                            <option value="float">Float</option>
                            <option value="combine">Combine</option>
                        </select>
                        @error('exchange_float_type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="exchange_float" class="block text-sm font-medium text-gray-200">Exchange Float</label>
                        <input type="text" name="exchange_float" id="exchange_float" class="mt-1 block w-full rounded-md border-[#293341] bg-[#1f2334] text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter exchange float" value="{{ old('exchange_float') }}">
                        @error('exchange_float')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-[#1f2334]">Create Asset</button>
                        <a href="{{ route('admin.assets.index') }}" class="px-4 py-2 bg-[#293341] text-gray-200 rounded-md hover:bg-[#353f4b] focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-[#1f2334]">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
