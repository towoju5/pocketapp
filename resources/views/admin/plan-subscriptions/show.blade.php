@extends('layouts.admin.app')

@section('title', 'Subscription #' . $subscription->id)

@section('content')
    <x-page-header :title="'Subscription #' . $subscription->id" subtitle="{{ $subscription->user->first_name }} {{ $subscription->user->last_name }}">
        <x-slot:actions>
            <x-badge :status="$subscription->status" />
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div><dt class="text-slate-400">Plan</dt><dd class="font-semibold text-white">{{ $subscription->plan->name ?? '—' }}</dd></div>
            <div><dt class="text-slate-400">Stake</dt><dd class="font-semibold text-white">{{ formatPrice($subscription->stake_amount) }}</dd></div>
            <div><dt class="text-slate-400">Expected Payout</dt><dd class="font-semibold text-white">{{ formatPrice($subscription->expected_payout) }}</dd></div>
            <div><dt class="text-slate-400">ROI</dt><dd class="font-semibold text-white">{{ $subscription->roi_percentage }}%</dd></div>
            <div><dt class="text-slate-400">Starts</dt><dd class="font-semibold text-white">{{ $subscription->starts_at->format('d M, Y H:i') }}</dd></div>
            <div><dt class="text-slate-400">Matures</dt><dd class="font-semibold text-white">{{ $subscription->matures_at->format('d M, Y H:i') }}</dd></div>
            <div><dt class="text-slate-400">Reinvest Count</dt><dd class="font-semibold text-white">{{ $subscription->reinvest_count }}</dd></div>
        </dl>

        @if ($subscription->status === 'active')
            <form method="POST" action="{{ route('admin.plan-subscriptions.cancel', $subscription) }}" class="mt-6" onsubmit="return confirm('Cancel and refund this subscription?')">
                @csrf
                <button type="submit" class="brand-btn bg-brand-danger text-white hover:bg-red-600">Cancel &amp; Refund</button>
            </form>
        @endif
    </x-glass-card>
@endsection
