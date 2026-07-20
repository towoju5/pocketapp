@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
<x-page-header title="User Management" subtitle="Admin-level control over every registered trading account.">
    <x-slot:actions>
        <span class="text-xs font-semibold text-slate-400">{{ $users->total() }} users</span>
    </x-slot:actions>
</x-page-header>

@if (session('success'))
    <div class="mb-6 rounded-xl border-l-4 border-brand-emerald bg-brand-emerald/10 px-5 py-4 text-sm font-semibold text-brand-emerald">
        {{ session('success') }}
    </div>
@endif

<x-glass-card padding="p-5">
    <x-data-table>
        <thead>
            <tr>
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">User</th>
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Wallet Balance</th>
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Actions</th>
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Quick Credit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="border-t border-glass-border px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-blue/10 font-bold text-brand-blue">
                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">
                                    {{ $user->first_name }} {{ $user->last_name }}
                                    @if (($user->kyc->status ?? null) === 'verified')
                                        <span class="ml-1 text-brand-emerald">&#10003;</span>
                                    @endif
                                </div>
                                <div class="font-mono text-xs text-slate-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="border-t border-glass-border px-4 py-4">
                        <div class="font-mono text-base font-bold text-white">{{ formatPrice($user->wallets->sum('balance')) }}</div>
                    </td>
                    <td class="border-t border-glass-border px-4 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="brand-btn-primary !px-3 !py-1.5 text-xs">Inspect</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="brand-btn-outline !px-3 !py-1.5 text-xs">Edit</a>
                        </div>
                    </td>
                    <td class="border-t border-glass-border px-4 py-4">
                        <form method="POST" action="{{ route('admin.wallets.credit', $user->id) }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="wallet" value="qt_real_usd">
                            <input type="number" step="0.01" name="amount" class="brand-input-dark !w-24 !py-1.5" placeholder="0.00" required>
                            <button type="submit" class="brand-btn-primary !px-3 !py-1.5 text-xs">Add</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="border-t border-glass-border px-4 py-8 text-center text-sm text-slate-400">No users found.</td></tr>
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-5">{{ $users->links() }}</div>
</x-glass-card>
@endsection
