@extends('layouts.app')

@section('title', 'Trade Details')
@section('content')
    <div class="container mx-auto py-6">
        <div class="bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 bg-gray-100">
                <h5 class="text-xl font-semibold">Trade Details #{{ $trade->id }}</h5>
                <a href="{{ route('trade.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500">Back to Trades</a>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 rounded-lg p-6">
                    <div>
                        <dl>
                            <dt class="font-semibold text-white">Asset</dt>
                            <dd class="text-gray-300">{{ $trade->trade_extra_info['asset'] }}</dd>

                            <dt class="font-semibold text-white mt-4">Amount</dt>
                            <dd class="text-gray-300">${{ number_format($trade->trade_extra_info['amount'], 6) }}</dd>

                            <dt class="font-semibold text-white mt-4">Direction</dt>
                            <dd class="text-gray-300">{{ ucfirst($trade->trade_direction) }}</dd>

                            <dt class="font-semibold text-white mt-4">Start Price</dt>
                            <dd class="text-gray-300">${{ number_format($trade->start_price, 6) }}</dd>
                        </dl>
                    </div>
                    <div>
                        <dl>
                            <dt class="font-semibold text-white">Current Price</dt>
                            <dd class="text-gray-300">${{ number_format($trade->trade_extra_info['currentPrice'], 6) }}</dd>

                            <dt class="font-semibold text-white mt-4">Status</dt>
                            <dd class="text-gray-300">
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $trade->trade_status === 'pending' ? 'bg-yellow-400 text-yellow-800' : 'bg-green-500 text-white' }}">
                                    {{ ucfirst($trade->trade_status) }}
                                </span>
                            </dd>

                            <dt class="font-semibold text-white mt-4">Close Time</dt>
                            <dd class="text-gray-300">{{ \Carbon\Carbon::parse($trade->trade_close_time)->format('Y-m-d H:i:s') }}</dd>

                            <dt class="font-semibold text-white mt-4">Date</dt>
                            <dd class="text-gray-300">{{ \Carbon\Carbon::parse($trade->created_at)->format('Y-m-d H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
