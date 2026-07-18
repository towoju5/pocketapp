@extends('layouts.desktop.trading')

@section('content')
    <div class="w-full mx-4">
        @include('partials.finance-header')
        <div class="w-full bg-gray-900 text-white">
            <div class="p-6">
                <div class="rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Balance History</h2>
                    <div class="flex flex-wrap items-center justify-between mb-4">
                        <div class="flex gap-2">
                            <a href="{{ url()->current() }}?type=deposit"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Deposits</button></a>
                            <a href="{{ url()->current() }}?type=withdraw"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Withdrawal</button></a>
                            <a href="{{ url()->current() }}?type=transfer"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">Internal Transfers</button></a>
                            <a href="{{ route('finance.history') }}"><button class="px-4 py-2 bg-[#1d2232] hover:border-blue-600 text-white rounded">All Types</button></a>
                        </div>
                        <form method="GET" action="{{ route('finance.history') }}" class="flex items-center gap-2">
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="px-3 py-2 rounded bg-gray-700 border-b border-[#292d4a] text-white text-sm" />
                            <span class="text-gray-400 text-sm">to</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="px-3 py-2 rounded bg-gray-700 border-b border-[#292d4a] text-white text-sm" />
                            <button type="submit" class="px-4 py-2 bg-[#292d4a] text-white rounded hover:bg-[#131a2c]">Apply</button>
                        </form>
                    </div>
                    <table class="table-auto w-full border-collapse shadow-xl text-sm">
                        <thead>
                            <tr class="py-2">
                                <th class="border-b border-[#292d4a] px-4 py-2">ID</th>
                                <th class="border-b border-[#292d4a] px-4 py-2">Date</th>
                                <th class="border-b border-[#292d4a] px-4 py-2">Amount</th>
                                <th class="border-b border-[#292d4a] px-4 py-2">Type</th>
                                <th class="border-b border-[#292d4a] px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $item)
                                <tr class="py-2 text-center">
                                    <td class="border-b border-gray-800 px-4 py-2">{{ $item->id }}</td>
                                    <td class="border-b border-gray-800 px-4 py-2">{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="border-b border-gray-800 px-4 py-2">${{ number_format($item->amountFloat, 2) }}</td>
                                    <td class="border-b border-gray-800 px-4 py-2">{{ ucfirst($item->type) }}</td>
                                    <td class="border-b border-gray-800 px-4 py-2">
                                        @if($item->confirmed)
                                            <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">Confirmed</span>
                                        @else
                                            <span class="bg-yellow-600 text-white px-2 py-1 rounded text-xs">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="flex items-center">
                                    <td colspan="5" class="py-2 text-center text-xl lg:text-2xl"> No Record found </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

