@extends('layouts.admin.app')

@section('title', 'Deposit Detail')

@section('content')
    <x-page-header :title="'Deposit: ' . $deposit->user->first_name . ' ' . $deposit->user->last_name" subtitle="Submitted {{ $deposit->created_at->format('d M, Y H:i') }}">
        <x-slot:actions>
            <x-badge :status="$deposit->deposit_status" />
        </x-slot:actions>
    </x-page-header>

    <x-glass-card title="Deposit Details" class="max-w-xl">
        <dl class="space-y-4 text-sm">
            <div class="flex justify-between"><dt class="text-slate-400">Amount</dt><dd class="font-mono font-semibold text-white">{{ formatPrice((float) $deposit->deposit_amount) }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">Method</dt><dd class="capitalize font-semibold text-white">{{ $deposit->deposit_method }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-400">User Email</dt><dd class="font-semibold text-white">{{ $deposit->user->email }}</dd></div>
            @if ($deposit->deposit_bonus)
                <div class="flex justify-between"><dt class="text-slate-400">Bonus</dt><dd class="font-semibold text-white">{{ $deposit->deposit_bonus }}</dd></div>
            @endif
        </dl>

        @if (!empty($deposit->deposit_extra_info))
            <div class="mt-6">
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Extra Info</p>
                <pre class="overflow-x-auto rounded-lg border border-glass-border bg-glass-surface-light p-4 text-xs text-slate-300">{{ json_encode($deposit->deposit_extra_info, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
    </x-glass-card>
@endsection
