@extends('layouts.admin.app')

@section('title', 'User: ' . $user->first_name)

@section('content')
    <x-page-header :title="$user->first_name . ' ' . $user->last_name" subtitle="{{ $user->email }}">
        <x-slot:actions>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="brand-btn-outline">Edit</a>
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Identity">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Username</dt><dd class="font-semibold text-white">{{ $user->username }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Phone</dt><dd class="font-semibold text-white">{{ $user->phone ?? 'N/A' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">KYC Status</dt><dd><x-badge :status="$user->kyc->status ?? 'unverified'" /></dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Joined</dt><dd class="font-semibold text-white">{{ $user->created_at->format('d M, Y') }}</dd></div>
            </dl>
        </x-glass-card>

        <x-glass-card title="Wallets" subtitle="Credit or debit a specific wallet.">
            <div class="mb-4 space-y-2">
                @foreach ($user->wallets as $wallet)
                    <div class="flex justify-between rounded-lg bg-white/5 px-4 py-2 text-sm">
                        <span class="capitalize text-slate-300">{{ $wallet->name }}</span>
                        <span class="font-mono font-semibold text-white">{{ number_format($wallet->balance, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('admin.wallets.update', $user->id) }}" method="POST" class="space-y-3">
                @csrf
                <select name="wallet" class="brand-input-dark" required>
                    <option value="" disabled selected>Choose wallet</option>
                    @foreach ($user->wallets as $wallet)
                        <option value="{{ $wallet->slug }}">{{ ucfirst($wallet->name) }} (Balance: {{ number_format($wallet->balance, 2) }})</option>
                    @endforeach
                </select>
                <select name="action" class="brand-input-dark" required>
                    <option value="" disabled selected>Select action</option>
                    <option value="credit">Credit</option>
                    <option value="debit">Debit</option>
                </select>
                <input type="number" name="amount" step="0.01" min="0.01" class="brand-input-dark" placeholder="Amount" required>
                <button type="submit" class="brand-btn-primary w-full justify-center">Submit</button>
            </form>
        </x-glass-card>
    </div>
@endsection
