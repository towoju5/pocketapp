@extends('layouts.admin.app')

@section('title', 'Treasury')

@php
    $globalLiquidity = $users->flatMap->wallets->sum('balance');
    $topNodes = $users->sortByDesc(fn ($u) => $u->wallets->sum('balance'))->take(10);
@endphp

@section('content')
    <x-page-header title="Treasury" subtitle="Platform-wide wallet balances." />

    <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-3">
        <x-stat-tile label="Global Liquidity" :value="formatPrice($globalLiquidity)" accent="emerald" icon="heroicon-o-banknotes" />
        <x-stat-tile label="Total Users" :value="$users->count()" accent="blue" icon="heroicon-o-users" />
        <x-stat-tile label="Avg. Balance" :value="formatPrice($users->count() ? $globalLiquidity / $users->count() : 0)" accent="amber" icon="heroicon-o-chart-bar" />
    </div>

    <x-glass-card title="Top Balances" subtitle="Top 10 wallet balances platform-wide. Open a user's profile to credit or debit a specific wallet.">
        <x-data-table>
            <thead><tr><th>User</th><th>Balance</th><th></th></tr></thead>
            <tbody>
                @forelse ($topNodes as $user)
                    <tr>
                        <td class="font-semibold text-white">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="font-mono font-semibold text-white">{{ formatPrice($user->wallets->sum('balance')) }}</td>
                        <td class="text-right"><a href="{{ route('admin.users.show', $user->id) }}" class="text-brand-blue hover:underline">Manage Wallets</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-10 text-slate-400">No wallets yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
