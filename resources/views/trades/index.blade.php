@extends('layouts.app')

@section('title', 'Trades')
@section('content')
<div class="w-full p-4">
    @include('partials.profile')
    <div class="bg-gray-900 text-white rounded-lg shadow-md">
        <div class="p-4 border-b border-gray-700">
            <h2 class="text-lg font-semibold">Trade Histories</h2>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Asset</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Entry Price</th>
                            <th class="px-4 py-3">Exit Price</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Direction</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900">
                        @foreach($trades as $trade)
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-3">{{ $trade->id }}</td>
                            <td class="px-4 py-3">{{ $trade->trade_currency ?? $trade->asset->name ?? "N/A" }}</td>
                            <td class="px-4 py-3">${{ number_format(floatval($trade->trade_amount), 6) }}</td>
                            <td class="px-4 py-3">{{ ucfirst($trade->trade_direction) }}</td>
                            <td class="px-4 py-3">${{ number_format(floatval($trade->start_price), 6) }}</td>
                            <td class="px-4 py-3">${{ number_format(floatval($trade->end_price), 6) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 rounded text-xs font-medium 
                                    {{ $trade->trade_status === 'win' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-black' }}">
                                    {{ ucfirst($trade->trade_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 flex gap-2 items-center">
                                @if($trade->trade_status === "pending")
                                    <span class="text-yellow-500 text-center">---</span>
                                @else
                                    {!! $trade->trade_profit == 1 ? '<i class="fa-solid fa-arrow-up arr-direction rounded-full bg-green-600 p-2 text-white"></i>Up' : '<i class="fa-solid fa-arrow-down arr-direction rounded-full bg-red-600 p-2 text-white"></i>Down' !!}
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                @if($isExpress === true)
                                <a href="{{ route('express.show', $trade->id) }}" 
                                   class="text-blue-500 hover:underline text-sm">View</a>
                                @else
                                <a href="{{ route('trade.show', $trade->id) }}" 
                                   class="text-blue-500 hover:underline text-sm">View</a>
                                @endif
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


@push('css')
<style>
    .arr-direction {
        rotate: 45deg;
    }
</style>
@endpush