@extends('layouts.desktop.trading')

@section('title', 'Investment Plans')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <div>
                <h1 class="text-xl font-bold text-white">Investment Plans</h1>
                <p class="text-sm text-[#7c86a3] mt-1">Stake capital into automated yield plans.</p>
            </div>
            <a href="{{ route('plans.subscriptions') }}" class="text-[#4f8ef7] font-semibold text-sm">My Subscriptions &rarr;</a>
        </div>

        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($plans as $plan)
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <div class="w-10 h-1 rounded-full mb-4" style="background: {{ $plan->badge_color ?? '#4f8ef7' }};"></div>
                    <h3 class="text-lg font-bold text-white mb-1">{{ $plan->name }}</h3>
                    <p class="text-xs font-bold text-[#4f8ef7] uppercase tracking-wide">{{ $plan->amount_type === 'fixed' ? 'Fixed' : 'Range' }} Allocation</p>

                    <div class="text-2xl font-bold text-white my-5">
                        @if ($plan->amount_type === 'fixed')
                            {{ formatPrice($plan->fixed_amount) }}
                        @else
                            {{ formatPrice($plan->min_amount) }}@if($plan->max_amount)<span class="text-sm text-[#7c86a3]"> &ndash; {{ formatPrice($plan->max_amount) }}</span>@endif
                        @endif
                    </div>

                    <div class="bg-[#1c243c] border border-[#2a3350] rounded-lg p-4 mb-5 text-sm space-y-2">
                        <div class="flex justify-between"><span class="text-[#7c86a3]">Yield</span><span class="text-[#16c087] font-bold">{{ $plan->roi_percentage }}%</span></div>
                        <div class="flex justify-between"><span class="text-[#7c86a3]">Duration</span><span class="text-white font-semibold">{{ $plan->duration_days }} days</span></div>
                        <div class="flex justify-between"><span class="text-[#7c86a3]">Capital</span><span class="text-white font-semibold">{{ $plan->capital_lock ? 'Locked' : 'Flexible' }}</span></div>
                    </div>

                    <a href="{{ route('plans.show', $plan) }}" class="block text-center bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-3 rounded-lg">View &amp; Subscribe</a>
                </div>
            @empty
                <div class="col-span-full bg-[#171e33] border border-dashed border-[#2a3350] rounded-xl p-12 text-center">
                    <p class="text-[#7c86a3] font-semibold">No active plans available right now.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
