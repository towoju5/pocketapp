@extends('layouts.desktop.trading')

@section('content')
    <div class="w-full mx-4">
        @include('partials.finance-header')
        <div class="w-full bg-[#171e33] border border-[#2a3350] text-[#d7dcea] rounded-xl">
            <div class="p-6">
                <div class="rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4 text-white">Balance History</h2>
                    <div class="flex flex-wrap items-center justify-between mb-4">
                        <div class="flex gap-2">
                            <a href="{{ url()->current() }}?type=deposit"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white rounded-lg">Deposits</button></a>
                            <a href="{{ url()->current() }}?type=withdraw"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white rounded-lg">Withdrawal</button></a>
                            <a href="{{ url()->current() }}?type=transfer"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white rounded-lg">Internal Transfers</button></a>
                            <a href="{{ route('finance.history') }}"><button class="px-4 py-2 bg-[#1c243c] border border-[#2a3350] hover:border-[#4f8ef7] text-white rounded-lg">All Types</button></a>
                        </div>
                        <form method="GET" action="{{ route('finance.history') }}" class="flex items-center gap-2">
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="px-3 py-2 rounded-lg bg-[#1c243c] border border-[#2a3350] text-white text-sm" />
                            <span class="text-[#7c86a3] text-sm">to</span>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="px-3 py-2 rounded-lg bg-[#1c243c] border border-[#2a3350] text-white text-sm" />
                            <button type="submit" class="px-4 py-2 bg-[#4f8ef7] text-white rounded-lg hover:bg-[#3d7de0]">Apply</button>
                        </form>
                    </div>
                    <table class="table-auto w-full border-collapse text-sm">
                        <thead>
                            <tr class="py-2">
                                <th class="border-b border-[#2a3350] px-4 py-2 text-[#7c86a3]">ID</th>
                                <th class="border-b border-[#2a3350] px-4 py-2 text-[#7c86a3]">Date</th>
                                <th class="border-b border-[#2a3350] px-4 py-2 text-[#7c86a3]">Amount</th>
                                <th class="border-b border-[#2a3350] px-4 py-2 text-[#7c86a3]">Type</th>
                                <th class="border-b border-[#2a3350] px-4 py-2 text-[#7c86a3]">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $item)
                                <tr class="py-2 text-center">
                                    <td class="border-b border-[#2a3350] px-4 py-2">{{ $item->id }}</td>
                                    <td class="border-b border-[#2a3350] px-4 py-2">{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="border-b border-[#2a3350] px-4 py-2">${{ number_format($item->amountFloat, 2) }}</td>
                                    <td class="border-b border-[#2a3350] px-4 py-2">{{ ucfirst($item->type) }}</td>
                                    <td class="border-b border-[#2a3350] px-4 py-2">
                                        @if($item->confirmed)
                                            <span class="bg-[#16c087]/15 text-[#16c087] px-2 py-1 rounded text-xs">Confirmed</span>
                                        @else
                                            <span class="bg-[#7c86a3]/15 text-[#7c86a3] px-2 py-1 rounded text-xs">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr class="flex items-center">
                                    <td colspan="5" class="py-2 text-center text-xl lg:text-2xl text-[#7c86a3]"> No Record found </td>
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
