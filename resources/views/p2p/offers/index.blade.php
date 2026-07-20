@extends('layouts.desktop.trading')

@section('title', 'P2P Escrow')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-white">P2P Escrow</h1>
                <p class="text-sm text-[#7c86a3] mt-1">Trade directly with other users under escrow protection.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('p2p-trades.index') }}" class="bg-[#1c243c] border border-[#2a3350] text-white text-sm font-semibold px-4 py-2.5 rounded-lg">My Trades</a>
                <a href="{{ route('p2p-offers.create') }}" class="bg-[#4f8ef7] hover:bg-[#3f7de6] text-white text-sm font-semibold px-4 py-2.5 rounded-lg">New Offer</a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-6">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        @if ($myOffers->isNotEmpty())
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-6">
                <h3 class="text-white font-bold mb-4">My Offers</h3>
                <div class="flex flex-col gap-2.5">
                    @foreach ($myOffers as $offer)
                        <div class="flex justify-between items-center bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3.5">
                            <div>
                                <span class="font-bold uppercase text-xs {{ $offer->type === 'buy' ? 'text-[#16c087]' : 'text-[#f4534a]' }}">{{ $offer->type }}</span>
                                <span class="text-white ml-2.5">{{ $offer->currency }} &bull; {{ formatPrice($offer->price_per_unit) }}/unit &bull; {{ formatPrice($offer->available_amount) }} available</span>
                            </div>
                            <div class="flex gap-2.5 items-center">
                                <span class="text-xs text-[#7c86a3] uppercase">{{ $offer->status }}</span>
                                @if ($offer->status === 'active')
                                    <form method="POST" action="{{ route('p2p-offers.cancel', $offer) }}">
                                        @csrf
                                        <button type="submit" class="bg-transparent border border-[#f4534a]/40 text-[#f4534a] text-xs px-3 py-1.5 rounded-lg">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($offers as $offer)
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <div class="flex justify-between items-center mb-3.5">
                        <span class="font-bold uppercase text-sm {{ $offer->type === 'buy' ? 'text-[#16c087]' : 'text-[#f4534a]' }}">{{ $offer->type }} {{ $offer->currency }}</span>
                        <span class="text-[#7c86a3] text-xs">{{ $offer->maker->first_name ?? 'Trader' }}</span>
                    </div>
                    <div class="text-2xl font-bold text-white mb-3.5">{{ formatPrice($offer->price_per_unit) }} <span class="text-xs text-[#7c86a3] font-normal">/ unit</span></div>
                    <div class="text-sm text-[#7c86a3] mb-5">
                        Limits: {{ formatPrice($offer->min_limit) }} &ndash; {{ formatPrice($offer->max_limit) }}<br>
                        Available: {{ formatPrice($offer->available_amount) }}
                    </div>
                    <a href="{{ route('p2p-trades.index') }}?offer={{ $offer->id }}" onclick="document.getElementById('trade-form-{{ $offer->id }}').classList.toggle('hidden'); return false;" class="block w-full text-center bg-[#4f8ef7] hover:bg-[#3f7de6] text-white text-sm font-semibold py-2.5 rounded-lg">Trade</a>

                    <form id="trade-form-{{ $offer->id }}" method="POST" action="{{ route('p2p-trades.store', $offer) }}" class="hidden mt-3.5">
                        @csrf
                        <input type="number" step="0.01" name="amount" placeholder="Amount" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white mb-2.5 focus:outline-none focus:border-[#4f8ef7]" required>
                        <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white text-sm font-semibold py-2.5 rounded-lg">Confirm Trade</button>
                    </form>
                </div>
            @empty
                <div class="col-span-full bg-[#171e33] border border-dashed border-[#2a3350] rounded-xl p-12 text-center">
                    <p class="text-[#7c86a3] font-semibold">No open offers right now.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5">{{ $offers->links() }}</div>
    </div>
</div>
@endsection
