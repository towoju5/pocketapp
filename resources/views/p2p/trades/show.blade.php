@extends('layouts.desktop.trading')

@php
    $isBuyer = $trade->buyer_id === auth()->id();
    $isSeller = $trade->seller_id === auth()->id();
    $counterparty = $isBuyer ? $trade->seller : $trade->buyer;
@endphp

@section('title', 'P2P Trade #' . $trade->id)

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto space-y-4">
        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <h2 class="text-lg font-bold text-white mb-1">Trade #{{ $trade->id }}</h2>
                    <p class="text-xs text-[#7c86a3]">You are the {{ $isBuyer ? 'buyer' : 'seller' }} in this escrow.</p>
                </div>
                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase bg-[#4f8ef7]/10 text-[#4f8ef7]">{{ str_replace('_', ' ', $trade->status) }}</span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-[#7c86a3]">Amount</dt><dd class="text-white font-bold text-lg">{{ formatPrice($trade->amount) }}</dd></div>
                <div><dt class="text-[#7c86a3]">Fiat Value</dt><dd class="text-white font-bold text-lg">{{ number_format($trade->fiat_amount, 2) }}</dd></div>
                <div><dt class="text-[#7c86a3]">Counterparty</dt><dd class="text-white font-semibold">{{ $counterparty->first_name ?? 'Trader' }} {{ $counterparty->last_name ?? '' }}</dd></div>
                <div><dt class="text-[#7c86a3]">Deadline</dt><dd class="text-white font-semibold">{{ $trade->payment_deadline->format('d M, H:i') }}</dd></div>
            </dl>

            @if ($trade->payment_proof_path)
                <div class="mt-5">
                    <p class="text-[#7c86a3] text-xs uppercase font-bold mb-2.5">Payment Proof</p>
                    <a href="{{ Storage::url($trade->payment_proof_path) }}" target="_blank" class="block rounded-lg overflow-hidden border border-[#2a3350]">
                        <img src="{{ Storage::url($trade->payment_proof_path) }}" class="w-full" alt="Payment proof">
                    </a>
                </div>
            @endif
        </div>

        @if ($isBuyer && $trade->status === 'pending_payment')
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <h3 class="text-white font-bold mb-4">Upload Payment Proof</h3>
                <form method="POST" action="{{ route('p2p-trades.pay', $trade) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="payment_proof" accept="image/*" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white mb-3.5 focus:outline-none focus:border-[#4f8ef7]" required>
                    <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-bold text-xs uppercase tracking-wide py-3 rounded-lg">I've Paid</button>
                </form>
            </div>
        @endif

        @if ($isSeller && $trade->status === 'paid')
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <h3 class="text-white font-bold mb-4">Release Escrow</h3>
                <p class="text-sm text-[#7c86a3] mb-4">Confirm you've received payment before releasing funds to the buyer.</p>
                <form method="POST" action="{{ route('p2p-trades.release', $trade) }}">
                    @csrf
                    <button type="submit" class="w-full bg-[#16c087] hover:bg-[#13a876] text-white font-bold text-xs uppercase tracking-wide py-3 rounded-lg">Confirm Receipt &amp; Release</button>
                </form>
            </div>
        @endif

        @if (in_array($trade->status, ['pending_payment', 'paid']))
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <div class="flex gap-3 flex-wrap">
                    @if ($trade->status === 'pending_payment')
                        <form method="POST" action="{{ route('p2p-trades.cancel', $trade) }}">
                            @csrf
                            <button type="submit" class="bg-transparent border border-[#2a3350] text-white font-bold text-xs uppercase tracking-wide px-5 py-3 rounded-lg">Cancel Trade</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('p2p-trades.dispute', $trade) }}" x-data="{ open: false }" @submit="if(!open){$event.preventDefault(); open = true}">
                        @csrf
                        <textarea x-show="open" name="reason" placeholder="Describe the issue..." class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white mb-2.5 w-72 focus:outline-none focus:border-[#4f8ef7]" required></textarea>
                        <button type="submit" class="bg-transparent border border-[#f4534a]/40 text-[#f4534a] font-bold text-xs uppercase tracking-wide px-5 py-3 rounded-lg">Raise Dispute</button>
                    </form>
                </div>
            </div>
        @endif

        @if ($trade->status === 'disputed')
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <p class="text-[#f4534a] font-semibold mb-2.5">Dispute raised {{ $trade->disputed_at->diffForHumans() }}</p>
                <p class="text-sm text-[#7c86a3] mb-4">{{ $trade->dispute_reason }}</p>
                @if ($trade->disputed_by === auth()->id())
                    <form method="POST" action="{{ route('p2p-trades.dispute.undo', $trade) }}">
                        @csrf
                        <button type="submit" class="bg-transparent border border-[#2a3350] text-white font-bold text-xs uppercase tracking-wide px-5 py-3 rounded-lg">Withdraw Dispute</button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
