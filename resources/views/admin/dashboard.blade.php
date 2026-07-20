@extends('layouts.admin.app')

@section('title', 'Dashboard')

@php
    $activeUsers = \App\Models\User::count();
    $totalLiquidity = \App\Models\User::with('wallets')->get()->flatMap->wallets->sum('balance');
    $pendingPh = \App\Models\Deposit::where('deposit_status', 'pending')->sum('deposit_amount');
    $pendingGh = \App\Models\Payout::where('payout_status', 'pending')->sum('payout_amount');
    $recentRequests = \App\Models\Deposit::with('user')->where('deposit_status', 'pending')
        ->latest()->limit(6)->get();

    $modules = [
        ['route' => 'admin.users.index', 'icon' => '👥', 'title' => 'Users', 'desc' => 'Manage user accounts'],
        ['route' => 'admin.wallets.index', 'icon' => '🏦', 'title' => 'Wallets', 'desc' => 'Balance adjustments'],
        ['route' => 'admin.deposits.index', 'icon' => '📥', 'title' => 'Deposits', 'desc' => 'Deposit oversight'],
        ['route' => 'admin.payouts.index', 'icon' => '📤', 'title' => 'Withdrawals', 'desc' => 'Approve/reject requests'],
        ['route' => 'admin.trades.index', 'icon' => '📈', 'title' => 'Trades', 'desc' => 'Binary options oversight'],
        ['route' => 'admin.express-trades.index', 'icon' => '⚡', 'title' => 'Express Trades', 'desc' => 'Fast-trade oversight'],
        ['route' => 'admin.p2p-trades.index', 'icon' => '⚖️', 'title' => 'P2P Trades', 'desc' => 'Escrow management'],
        ['route' => 'admin.tasks.index', 'icon' => '🎯', 'title' => 'Tasks', 'desc' => 'Task configuration'],
        ['route' => 'admin.kyc.index', 'icon' => '🪪', 'title' => 'KYC', 'desc' => 'Verification queue'],
        ['route' => 'admin.support-tickets.index', 'icon' => '🎧', 'title' => 'Support', 'desc' => 'Ticket monitoring'],
        ['route' => 'admin.cashbacks.index', 'icon' => '💸', 'title' => 'Cashback', 'desc' => 'Loss/volume rules'],
        ['route' => 'admin.promo-codes.index', 'icon' => '🎁', 'title' => 'Promo Codes', 'desc' => 'Discount codes'],
        ['route' => 'admin.settings.index', 'icon' => '⚙️', 'title' => 'Settings', 'desc' => 'Platform toggles'],
    ];
@endphp

@section('content')
<x-page-header title="Dashboard" subtitle="Platform overview and quick access to management tools." />

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat-tile label="Total Liquidity" :value="formatPrice($totalLiquidity)" accent="blue" hint="Across all user wallets" />
    <x-stat-tile label="Active Users" :value="number_format($activeUsers)" accent="emerald" hint="Registered accounts" />
    <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}">
        <x-stat-tile label="Pending Deposits" :value="formatPrice($pendingPh)" accent="amber" hint="Awaiting confirmation" />
    </a>
    <a href="{{ route('admin.payouts.index', ['status' => 'pending']) }}">
        <x-stat-tile label="Pending Withdrawals" :value="formatPrice($pendingGh)" accent="danger" hint="Awaiting settlement" />
    </a>
</div>

<x-glass-card title="Recent Deposit Requests" class="mt-6" padding="p-5">
    <x-slot:actions>
        @if (Route::has('admin.deposits.index'))
            <a href="{{ route('admin.deposits.index') }}" class="text-xs font-semibold text-brand-blue hover:text-brand-blue-hover">View all &rarr;</a>
        @endif
    </x-slot:actions>

    <x-data-table>
        <thead>
            <tr>
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">User</th>
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Type</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">Amount</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-400">Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentRequests as $req)
                <tr>
                    <td class="border-t border-glass-border px-4 py-3 font-semibold text-white">{{ $req->user->first_name ?? 'User' }} {{ $req->user->last_name ?? '' }}</td>
                    <td class="border-t border-glass-border px-4 py-3"><x-badge status="success">Deposit</x-badge></td>
                    <td class="border-t border-glass-border px-4 py-3 text-right font-mono font-semibold text-white">{{ formatPrice($req->deposit_amount) }}</td>
                    <td class="border-t border-glass-border px-4 py-3 text-right text-xs text-slate-400">{{ $req->created_at->format('H:i:s') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="border-t border-glass-border px-4 py-8 text-center text-sm text-slate-400">No pending deposit requests.</td></tr>
            @endforelse
        </tbody>
    </x-data-table>
</x-glass-card>

<h3 class="mt-8 mb-4 text-xs font-bold uppercase tracking-wide text-slate-400">Management</h3>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($modules as $module)
        @if (Route::has($module['route']))
            <a href="{{ route($module['route']) }}" class="glass-panel flex items-center gap-4 p-5 hover:border-brand-blue">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-glass-surface-light text-xl">{{ $module['icon'] }}</span>
                <div>
                    <p class="text-sm font-bold text-white">{{ $module['title'] }}</p>
                    <p class="text-xs text-slate-400">{{ $module['desc'] }}</p>
                </div>
            </a>
        @endif
    @endforeach
</div>
@endsection
