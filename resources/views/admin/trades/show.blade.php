@extends('layouts.admin.app')

@section('title', 'Trade Detail')

@section('content')
    <x-page-header :title="'Trade #' . $trade->id" subtitle="Placed {{ $trade->created_at->format('d M, Y H:i:s') }}">
        <x-slot:actions>
            <x-badge :status="$trade->trade_status === 'lose' ? 'danger' : ($trade->trade_status === 'win' ? 'success' : 'pending')">{{ ucfirst($trade->trade_status) }}</x-badge>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Trade Details">
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">User</dt><dd class="font-semibold text-white">{{ $trade->user->first_name ?? 'User' }} {{ $trade->user->last_name ?? '' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Asset</dt><dd class="font-semibold text-white">{{ $trade->trade_currency }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Direction</dt><dd class="font-semibold {{ $trade->trade_direction === 'up' ? 'text-brand-emerald' : 'text-brand-danger' }}">{{ $trade->trade_direction === 'up' ? 'BUY' : 'SELL' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Amount</dt><dd class="font-mono font-semibold text-white">{{ formatPrice($trade->trade_amount) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Start Price</dt><dd class="font-mono font-semibold text-white">{{ $trade->start_price }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">End Price</dt><dd class="font-mono font-semibold text-white">{{ $trade->end_price ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Profit %</dt><dd class="font-semibold text-white">{{ $trade->trade_percentage }}%</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Wallet</dt><dd class="text-xs uppercase text-slate-300">{{ str_contains($trade->trade_wallet, 'real') ? 'Real' : 'Demo' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Closes At</dt><dd class="text-white">{{ $trade->trade_close_time }}</dd></div>
            </dl>

            @if ($trade->admin_action_by)
                <div class="mt-6 rounded-xl border border-brand-amber/20 bg-brand-amber/10 p-4 text-sm">
                    <p class="font-semibold text-brand-amber">Admin action: {{ strtoupper($trade->admin_forced_outcome) }}</p>
                    <p class="mt-1 text-slate-300">{{ $trade->admin_action_notes }}</p>
                    <p class="mt-2 text-xs text-slate-400">
                        {{ $trade->adminActionBy->first_name ?? 'Admin' }} {{ $trade->adminActionBy->last_name ?? '' }}
                        &middot; {{ $trade->admin_action_at?->format('d M, Y H:i:s') }}
                    </p>
                </div>
            @endif
        </x-glass-card>

        <x-glass-card title="Manual Settlement">
            @if ($trade->trade_status === 'pending' || $trade->trade_status === 'open')
                <p class="mb-4 text-sm text-slate-400">
                    Overrides how this trade settles instead of waiting for its natural close time. Applies immediately —
                    the customer's wallet is credited/refunded right away. This applies to both demo and real-money trades.
                </p>

                <form method="POST" action="{{ route('admin.trades.force-win', $trade) }}" class="space-y-3" onsubmit="return confirm('Force this trade to WIN? The payout will be credited immediately.');">
                    @csrf
                    <textarea name="notes" rows="2" placeholder="Reason (required, kept on the trade's audit trail)" class="brand-input-dark" required></textarea>
                    <button type="submit" class="brand-btn-primary w-full justify-center !bg-brand-emerald">
                        <i class="fa fa-arrow-up"></i> Force Win
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.trades.force-lose', $trade) }}" class="mt-3 space-y-3" onsubmit="return confirm('Force this trade to LOSE?');">
                    @csrf
                    <textarea name="notes" rows="2" placeholder="Reason (required, kept on the trade's audit trail)" class="brand-input-dark" required></textarea>
                    <button type="submit" class="brand-btn-primary w-full justify-center !bg-brand-danger">
                        <i class="fa fa-arrow-down"></i> Force Lose
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.trades.void', $trade) }}" class="mt-3 space-y-3" onsubmit="return confirm('Void this trade and refund the stake?');">
                    @csrf
                    <textarea name="notes" rows="2" placeholder="Reason (required, kept on the trade's audit trail)" class="brand-input-dark" required></textarea>
                    <button type="submit" class="brand-btn-secondary w-full justify-center">
                        <i class="fa fa-rotate-left"></i> Void &amp; Refund Stake
                    </button>
                </form>
            @else
                <p class="text-sm text-slate-400">This trade has already settled — no further action available.</p>
            @endif
        </x-glass-card>
    </div>
@endsection
