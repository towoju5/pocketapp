@extends('layouts.admin.app')

@section('title', 'Express Trade Detail')

@section('content')
    <x-page-header :title="'Express Trade #' . $trade->id" subtitle="Placed {{ $trade->created_at->format('d M, Y H:i:s') }}">
        <x-slot:actions>
            <x-badge :status="$trade->trade_status === 'lose' ? 'danger' : ($trade->trade_status === 'win' ? 'success' : 'pending')">{{ ucfirst($trade->trade_status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card title="Trade Details" class="max-w-xl">
        <dl class="space-y-4 text-sm">
            <div class="flex justify-between"><dt class="text-slate-400">User</dt><dd class="font-semibold text-white">{{ $trade->user->first_name ?? 'User' }} {{ $trade->user->last_name ?? '' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Asset</dt><dd class="font-semibold text-white">{{ $trade->asset->symbol ?? $trade->trade_currency }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Direction</dt><dd class="font-semibold {{ $trade->trade_direction === 'up' ? 'text-brand-emerald' : 'text-brand-danger' }}">{{ $trade->trade_direction === 'up' ? 'BUY' : 'SELL' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Amount</dt><dd class="font-mono font-semibold text-white">{{ formatPrice($trade->trade_amount) }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Start Price</dt><dd class="font-mono font-semibold text-white">{{ $trade->start_price }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">End Price</dt><dd class="font-mono font-semibold text-white">{{ $trade->end_price ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Profit %</dt><dd class="font-semibold text-white">{{ $trade->trade_percentage }}%</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Wallet</dt><dd class="text-xs uppercase text-slate-300">{{ $trade->trade_wallet }}</dd></div>
        </dl>
    </x-glass-card>
@endsection
