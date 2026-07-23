@extends('layouts.desktop.trading')

@section('title', 'P2P Escrow')

@section('content')
<div class="flex-1 overflow-y-auto p-4 sm:p-6">
    <div class="mx-auto max-w-[1400px]">

        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-white flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(79,142,247,0.15);">
                        <i class="fa fa-arrow-right-arrow-left text-[#4f8ef7] text-sm"></i>
                    </span>
                    P2P Escrow
                </h1>
                <p class="text-sm text-[#7c86a3] mt-1">Trade directly with other users under escrow protection.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('p2p-trades.index') }}" class="bg-[#1c243c] border border-[#2a3350] text-white text-sm font-semibold px-4 py-2.5 rounded-lg hover:border-[#4f8ef7] transition-colors">
                    <i class="fa fa-list-ul mr-1.5 text-[#7c86a3]"></i>My Trades
                </a>
                <a href="{{ route('p2p-offers.create') }}" class="bg-[#4f8ef7] hover:bg-[#3f7de6] text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                    <i class="fa fa-plus mr-1.5"></i>New Offer
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        {{-- Buy/Sell filter — P2pOfferController::index already supports
             ?type=buy|sell, it just had no UI wired to it. --}}
        <div class="flex gap-2 mb-6">
            @php $currentType = request('type'); @endphp
            <a href="{{ route('p2p-offers.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ !$currentType ? 'bg-[#4f8ef7] text-white' : 'bg-[#1c243c] border border-[#2a3350] text-[#7c86a3]' }}">All Offers</a>
            <a href="{{ route('p2p-offers.index', ['type' => 'buy']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $currentType === 'buy' ? 'bg-[#16c087] text-white' : 'bg-[#1c243c] border border-[#2a3350] text-[#7c86a3]' }}">Buy</a>
            <a href="{{ route('p2p-offers.index', ['type' => 'sell']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $currentType === 'sell' ? 'bg-[#f4534a] text-white' : 'bg-[#1c243c] border border-[#2a3350] text-[#7c86a3]' }}">Sell</a>
        </div>

        @if ($myOffers->isNotEmpty())
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 sm:p-6 mb-6">
                <h3 class="text-white font-bold mb-4 flex items-center gap-2"><i class="fa fa-user text-[#7c86a3] text-sm"></i>My Offers</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    @foreach ($myOffers as $offer)
                        <div class="flex justify-between items-center gap-3 bg-[#1c243c] border-l-4 {{ $offer->type === 'buy' ? 'border-l-[#16c087]' : 'border-l-[#f4534a]' }} border-y border-r border-[#2a3350] rounded-lg px-4 py-3.5">
                            <div class="min-w-0">
                                <span class="font-bold uppercase text-xs {{ $offer->type === 'buy' ? 'text-[#16c087]' : 'text-[#f4534a]' }}">{{ $offer->type }}</span>
                                <div class="text-white text-sm truncate">{{ $offer->currency }} &bull; {{ formatPrice($offer->price_per_unit) }}/unit</div>
                                <div class="text-xs text-[#7c86a3] truncate">{{ formatPrice($offer->available_amount) }} available</div>
                            </div>
                            <div class="flex flex-col gap-2 items-end flex-shrink-0">
                                <span class="text-[10px] text-[#7c86a3] uppercase font-bold">{{ $offer->status }}</span>
                                @if ($offer->status === 'active')
                                    <form method="POST" action="{{ route('p2p-offers.cancel', $offer) }}">
                                        @csrf
                                        <button type="submit" class="bg-transparent border border-[#f4534a]/40 text-[#f4534a] text-xs px-3 py-1.5 rounded-lg hover:bg-[#f4534a]/10 transition-colors">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse ($offers as $offer)
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 flex flex-col hover:border-[#4f8ef7]/50 transition-colors">
                    <div class="flex justify-between items-center mb-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase {{ $offer->type === 'buy' ? 'bg-[#16c087]/15 text-[#16c087]' : 'bg-[#f4534a]/15 text-[#f4534a]' }}">{{ $offer->type }} {{ $offer->currency }}</span>
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="w-6 h-6 rounded-full bg-[#33406b] flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                {{ strtoupper(substr($offer->maker->first_name ?? 'T', 0, 1)) }}
                            </span>
                            <span class="text-[#7c86a3] text-xs truncate max-w-[80px]">{{ $offer->maker->first_name ?? 'Trader' }}</span>
                        </div>
                    </div>

                    <div class="text-2xl font-bold text-white mb-1">{{ formatPrice($offer->price_per_unit) }}</div>
                    <div class="text-xs text-[#7c86a3] mb-4">per unit</div>

                    <div class="flex flex-col gap-1.5 text-sm mb-4 pb-4 border-b border-[#2a3350]">
                        <div class="flex justify-between"><span class="text-[#7c86a3]">Limits</span><span class="text-[#d7dcea]">{{ formatPrice($offer->min_limit) }} &ndash; {{ formatPrice($offer->max_limit) }}</span></div>
                        <div class="flex justify-between"><span class="text-[#7c86a3]">Available</span><span class="text-[#d7dcea]">{{ formatPrice($offer->available_amount) }}</span></div>
                    </div>

                    @if (!empty($offer->payment_methods))
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach ($offer->payment_methods as $method)
                                <span class="text-xs bg-[#1c243c] border border-[#2a3350] text-[#7c86a3] px-2 py-1 rounded-md">{{ $method }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-auto">
                        <a href="{{ route('p2p-trades.index') }}?offer={{ $offer->id }}" onclick="document.getElementById('trade-form-{{ $offer->id }}').classList.toggle('hidden'); return false;" class="block w-full text-center bg-[#4f8ef7] hover:bg-[#3f7de6] text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">Trade</a>

                        <form id="trade-form-{{ $offer->id }}" method="POST" action="{{ route('p2p-trades.store', $offer) }}" class="hidden mt-3.5 pt-3.5 border-t border-[#2a3350]">
                            @csrf
                            <input type="number" step="0.01" name="amount" placeholder="Amount" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white mb-2.5 focus:outline-none focus:border-[#4f8ef7]" required>
                            <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white text-sm font-semibold py-2.5 rounded-lg transition-colors">Confirm Trade</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-[#171e33] border border-dashed border-[#2a3350] rounded-xl p-12 text-center">
                    <i class="fa fa-inbox text-[#7c86a3] text-3xl mb-3"></i>
                    <p class="text-[#7c86a3] font-semibold">No open offers right now.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5">{{ $offers->links() }}</div>
    </div>
</div>
@endsection
