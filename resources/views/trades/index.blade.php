@extends('layouts.app')

@section('title', 'Trades')
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-gray-800 text-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold">Trade Histories</h2>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="bg-gray-700 text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Asset</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Entry Price</th>
                            <th class="px-4 py-3">Exit Price</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Profit/Loss</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800">
                        @foreach($trades as $trade)
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-3">{{ $trade->id }}</td>
                            <td class="px-4 py-3">{{ $trade->trade_currency }}</td>
                            <td class="px-4 py-3">${{ number_format(floatval($trade->trade_amount), 2) }}</td>
                            <td class="px-4 py-3">{{ ucfirst($trade->trade_direction) }}</td>
                            <td class="px-4 py-3">${{ number_format(floatval($trade->start_price), 2) }}</td>
                            <td class="px-4 py-3">${{ number_format(floatval($trade->end_price), 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium 
                                    {{ $trade->trade_status === 'win' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-black' }}">
                                    {{ ucfirst($trade->trade_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $trade->trade_profit == 1 ? 'Profit' : 'Loss' }}
                            </td>
                            <td class="px-4 py-3">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('trade.show', $trade->id) }}" 
                                   class="text-blue-500 hover:underline text-sm">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $trades->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection
