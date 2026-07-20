@extends('layouts.desktop.trading')

@section('title', 'Payout History')
@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Payout History</h1>
            <a href="{{ route('payout.create') }}" class="bg-[#4f8ef7] text-white text-sm font-semibold px-4 py-2 rounded-lg">Request Withdrawal</a>
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
                        @forelse($payouts as $payout)
                            <tr class="border-t border-[#1c243c]">
                                <td class="px-4 py-3 text-[#7c86a3]">{{ \Illuminate\Support\Carbon::parse($payout->payout_date_time)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 font-semibold">${{ number_format((float) $payout->payout_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ ucfirst($payout->payout_method) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $payout->payout_status === 'completed' ? 'bg-[#16c087]/15 text-[#16c087]' : ($payout->payout_status === 'rejected' ? 'bg-[#f4534a]/15 text-[#f4534a]' : 'bg-[#7c86a3]/15 text-[#7c86a3]') }}">
                                        {{ ucfirst($payout->payout_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('payout.show', $payout->id) }}" class="text-[#4f8ef7] text-xs hover:underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-[#7c86a3]">No withdrawal requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
