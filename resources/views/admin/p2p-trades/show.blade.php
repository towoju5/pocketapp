@extends('layouts.admin.app')

@section('title', 'P2P Trade #' . $trade->id)

@section('content')
    <x-page-header :title="'Trade #' . $trade->id">
        <x-slot:actions><x-badge :status="$trade->status" /></x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Details">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Buyer</dt><dd class="font-semibold text-white">{{ $trade->buyer->first_name }} {{ $trade->buyer->last_name }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Seller</dt><dd class="font-semibold text-white">{{ $trade->seller->first_name }} {{ $trade->seller->last_name }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Amount</dt><dd class="font-semibold text-white">{{ formatPrice($trade->amount) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Fiat Value</dt><dd class="font-semibold text-white">{{ number_format($trade->fiat_amount, 2) }}</dd></div>
            </dl>

            @if ($trade->payment_proof_path)
                <div class="mt-6">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Payment Proof</p>
                    <a href="{{ Storage::url($trade->payment_proof_path) }}" target="_blank" class="block overflow-hidden rounded-xl border border-glass-border">
                        <img src="{{ Storage::url($trade->payment_proof_path) }}" class="w-full" alt="Payment proof">
                    </a>
                </div>
            @endif
        </x-glass-card>

        <x-glass-card title="Dispute">
            @if ($trade->status === 'disputed')
                <p class="mb-4 rounded-xl border border-brand-danger/20 bg-brand-danger/10 p-4 text-sm text-brand-danger">{{ $trade->dispute_reason }}</p>

                <form method="POST" action="{{ route('admin.p2p-trades.resolve', $trade) }}" class="space-y-3">
                    @csrf
                    <select name="decision" class="brand-input-dark" required>
                        <option value="buyer">Resolve in favor of Buyer</option>
                        <option value="seller">Resolve in favor of Seller</option>
                    </select>
                    <textarea name="notes" rows="3" placeholder="Resolution notes" class="brand-input-dark" required></textarea>
                    <button type="submit" class="brand-btn-primary w-full justify-center">Resolve Dispute</button>
                </form>
            @else
                <p class="text-sm text-slate-400">This trade is not currently disputed.</p>
            @endif
        </x-glass-card>
    </div>
@endsection
