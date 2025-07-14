@extends('layouts.app')

@section('title', 'Trades')
@section('content')
<div class="w-full p-4">
    @if(!is_mobile())
    @include('partials.profile')
    @endif

    <div class="panel box-border personal-info-panel mb-3">
        <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl">
            <div class="panel-title text-lg font-semibold text-gray-100">Trade Histories</div>
        </div>
        @if(is_mobile())
        <div class="w-full border border-[#292d4a] rounded-b-xl p-3">
            @foreach($trades as $trade)
            <div class="bg-[#1b1e35] border border-[#292d4a] text-white p-4 shadow-lg rounded-lg my-3">
                <div class="p-2 text-sm space-y-2">
                    <div class="flex justify-between border-b border-[#292d4a] pb-2">
                        <span class="text-gray-400">Trade ID:</span>
                        <span class="font-medium break-all">{{ $trade->id }}</span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Asset:</span>
                        <span class="font-medium">{{ $trade->trade_currency ?? $trade->asset->name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Amount:</span>
                        <span class="font-medium text-green-400">${{ number_format(floatval($trade->trade_amount), 6) }}</span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Type:</span>
                        <span class="font-medium">{{ ucfirst($trade->trade_direction) }}</span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Entry Price:</span>
                        <span class="font-medium">${{ number_format(floatval($trade->start_price), 6) }}</span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Exit Price:</span>
                        <span class="font-medium">${{ number_format(floatval($trade->end_price), 6) }}</span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Status:</span>
                        <span class="font-medium">
                            <span class="inline-block px-2 py-1 rounded text-xs font-medium 
                                {{ $trade->trade_status === 'win' ? 'bg-green-500 text-white' : 'bg-yellow-500 text-black' }}">
                                {{ ucfirst($trade->trade_status) }}
                            </span>
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Direction:</span>
                        <span class="font-medium flex items-center gap-2">
                            @if($trade->trade_status === 'pending')
                                <span class="text-yellow-500">---</span>
                            @else
                                @if($trade->trade_profit == 1)
                                    <i class="fa-solid fa-arrow-up rounded-full bg-green-600 p-1 text-white text-xs"></i> Up
                                @else
                                    <i class="fa-solid fa-arrow-down rounded-full bg-red-600 p-1 text-white text-xs"></i> Down
                                @endif
                            @endif
                        </span>
                    </div>

                    <div class="flex justify-between border-b border-[#292d4a] py-2">
                        <span class="text-gray-400">Date:</span>
                        <span class="font-medium">{{ $trade->created_at->format('Y-m-d H:i') }}</span>
                    </div>

                    <div class="flex justify-between pt-2">
                        <span class="text-gray-400">Action:</span>
                        <span class="font-medium">
                            @if($isExpress === true)
                                <a href="{{ route('express.show', $trade->id) }}" class="text-blue-400 underline">View</a>
                            @else
                                <a href="{{ route('trade.show', $trade->id) }}" class="text-blue-400 underline">View</a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="mt-4">
                {{ $trades->links('pagination::tailwind') }}
            </div>
        </div>
        @else
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
        @endif
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