@extends('layouts.desktop.trading')

@section('title', 'My P2P Trades')

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6">
    <div class="mx-auto max-w-[1000px]">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <h1 class="text-xl font-bold text-white flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(79,142,247,0.15);">
                    <i class="fa fa-list-ul text-[#4f8ef7] text-sm"></i>
                </span>
                My P2P Trades
            </h1>
            <a href="{{ route('p2p-offers.index') }}" class="text-[#4f8ef7] font-semibold text-sm">&larr; Browse Offers</a>
        </div>

        @php
            $statusColors = [
                'pending_payment' => 'bg-[#f7b84f]/15 text-[#f7b84f]',
                'paid' => 'bg-[#4f8ef7]/15 text-[#4f8ef7]',
                'released' => 'bg-[#16c087]/15 text-[#16c087]',
                'disputed' => 'bg-[#f4534a]/15 text-[#f4534a]',
                'cancelled' => 'bg-[#7c86a3]/15 text-[#7c86a3]',
            ];
        @endphp

        <div class="flex flex-col gap-3">
            @forelse ($trades as $trade)
                @php $isSeller = $trade->seller_id === auth()->id(); @endphp
                <a href="{{ route('p2p-trades.show', $trade) }}" class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 flex items-center justify-between gap-4 flex-wrap hover:border-[#4f8ef7]/50 transition-colors">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <span class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $isSeller ? 'bg-[#f4534a]/15' : 'bg-[#16c087]/15' }}">
                            <i class="fa {{ $isSeller ? 'fa-arrow-up' : 'fa-arrow-down' }} {{ $isSeller ? 'text-[#f4534a]' : 'text-[#16c087]' }}"></i>
                        </span>
                        <div class="min-w-0">
                            <div class="font-semibold text-white">Trade #{{ $trade->id }}</div>
                            <div class="text-xs text-[#7c86a3]">{{ $isSeller ? 'Selling' : 'Buying' }} &bull; {{ formatPrice($trade->amount) }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase {{ $statusColors[$trade->status] ?? 'bg-[#7c86a3]/15 text-[#7c86a3]' }}">{{ str_replace('_', ' ', $trade->status) }}</span>
                        <span class="text-[#4f8ef7] font-semibold text-sm flex-shrink-0">View &rarr;</span>
                    </div>
                </a>
            @empty
                <div class="bg-[#171e33] border border-dashed border-[#2a3350] rounded-xl p-12 text-center">
                    <i class="fa fa-inbox text-[#7c86a3] text-3xl mb-3"></i>
                    <p class="text-[#7c86a3] font-semibold">No trades yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5">{{ $trades->links() }}</div>
    </div>
</div>
@endsection
