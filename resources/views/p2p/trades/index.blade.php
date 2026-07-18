@extends('layouts.desktop.trading')

@section('title', 'My P2P Trades')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <h1 class="text-xl font-bold text-white">My P2P Trades</h1>
            <a href="{{ route('p2p-offers.index') }}" class="text-[#4f8ef7] font-semibold text-sm">&larr; Browse Offers</a>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase">
                        <tr>
                            <th class="py-2 px-3">Trade</th>
                            <th class="py-2 px-3">Role</th>
                            <th class="py-2 px-3">Amount</th>
                            <th class="py-2 px-3">Status</th>
                            <th class="py-2 px-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse ($trades as $trade)
                            <tr class="border-t border-[#1c243c]">
                                <td class="py-3 px-3 font-semibold text-white">#{{ $trade->id }}</td>
                                <td class="py-3 px-3 text-xs text-[#7c86a3]">{{ $trade->seller_id === auth()->id() ? 'Seller' : 'Buyer' }}</td>
                                <td class="py-3 px-3">{{ formatPrice($trade->amount) }}</td>
                                <td class="py-3 px-3">
                                    @php
                                        $statusColors = [
                                            'pending_payment' => 'bg-[#f7b84f]/10 text-[#f7b84f]',
                                            'paid' => 'bg-[#4f8ef7]/10 text-[#4f8ef7]',
                                            'released' => 'bg-[#16c087]/10 text-[#16c087]',
                                            'disputed' => 'bg-[#f4534a]/10 text-[#f4534a]',
                                            'cancelled' => 'bg-[#7c86a3]/10 text-[#7c86a3]',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase {{ $statusColors[$trade->status] ?? 'bg-[#7c86a3]/10 text-[#7c86a3]' }}">{{ str_replace('_', ' ', $trade->status) }}</span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('p2p-trades.show', $trade) }}" class="text-[#4f8ef7] font-semibold text-sm">View &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-12 text-[#7c86a3] font-semibold">No trades yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5">{{ $trades->links() }}</div>
        </div>
    </div>
</div>
@endsection
