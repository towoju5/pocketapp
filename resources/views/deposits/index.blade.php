@extends('layouts.desktop.trading')

@section('title', 'Deposit History')
@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Deposit History</h1>
            <a href="{{ route('deposits.create') }}" class="bg-[#4f8ef7] text-white text-sm font-semibold px-4 py-2 rounded-lg">New Deposit</a>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase bg-[#1c243c]">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Method</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse($deposits as $deposit)
                            <tr class="border-t border-[#1c243c]">
                                <td class="px-4 py-3 text-[#7c86a3]">{{ \Illuminate\Support\Carbon::parse($deposit->deposit_date_time)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 font-semibold">${{ number_format((float) $deposit->deposit_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($deposit->deposit_method) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $deposit->deposit_status === 'completed' ? 'bg-[#16c087]/15 text-[#16c087]' : ($deposit->deposit_status === 'rejected' ? 'bg-[#f4534a]/15 text-[#f4534a]' : 'bg-[#7c86a3]/15 text-[#7c86a3]') }}">
                                        {{ ucfirst($deposit->deposit_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('deposits.show', $deposit->id) }}" class="text-[#4f8ef7] text-xs hover:underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-[#7c86a3]">No deposits yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-[#2a3350]">
                {{ $deposits->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
