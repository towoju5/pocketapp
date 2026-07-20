@extends('layouts.admin.app')

@section('title', 'Review Withdrawal')

@section('content')
    <x-page-header :title="'Review: ' . $payout->user->first_name . ' ' . $payout->user->last_name" subtitle="Requested {{ $payout->created_at->format('d M, Y H:i') }}">
        <x-slot:actions>
            <x-badge :status="$payout->payout_status" />
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Withdrawal Details">
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Amount</dt><dd class="font-mono font-semibold text-white">{{ formatPrice((float) $payout->payout_amount) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Method</dt><dd class="capitalize font-semibold text-white">{{ $payout->payout_method }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Destination Address</dt><dd class="font-mono text-xs font-semibold text-white break-all">{{ $payout->payout_bonus }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">User Email</dt><dd class="font-semibold text-white">{{ $payout->user->email }}</dd></div>
            </dl>

            @if (($payout->payout_extra_info['rejection_reason'] ?? null))
                <p class="mt-6 rounded-xl border border-brand-danger/20 bg-brand-danger/10 p-4 text-sm text-brand-danger">{{ $payout->payout_extra_info['rejection_reason'] }}</p>
            @endif
        </x-glass-card>

        <x-glass-card title="Decision">
            @if ($payout->payout_status === 'pending')
                <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}" class="mb-4">
                    @csrf
                    <button type="submit" class="brand-btn-primary w-full justify-center">Approve &amp; Mark Completed</button>
                </form>

                <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}" class="space-y-3">
                    @csrf
                    <textarea name="reason" rows="3" required placeholder="Rejection reason (funds will be returned to the user)" class="brand-input-dark"></textarea>
                    <button type="submit" class="brand-btn w-full justify-center bg-brand-danger text-white hover:bg-red-600">Reject &amp; Refund</button>
                </form>
            @else
                <p class="text-sm text-slate-400">This withdrawal has already been {{ $payout->payout_status }}.</p>
            @endif
        </x-glass-card>
    </div>
@endsection
