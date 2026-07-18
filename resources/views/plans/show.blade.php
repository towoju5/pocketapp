@extends('layouts.desktop.trading')

@section('title', $plan->name)

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-xl mx-auto">
        @if ($errors->any())
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ $errors->first() }}</div>
        @endif
        @if (session('error'))
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ session('error') }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <div class="w-10 h-1 rounded-full mb-4" style="background: {{ $plan->badge_color ?? '#4f8ef7' }};"></div>
            <h1 class="text-xl font-bold text-white mb-1">{{ $plan->name }}</h1>
            <p class="text-sm text-[#7c86a3] mb-6">{{ $plan->roi_percentage }}% yield over {{ $plan->duration_days }} days &bull; {{ $plan->capital_lock ? 'Capital locked' : 'Flexible capital' }}</p>

            <form method="POST" action="{{ route('plans.subscribe', $plan) }}">
                @csrf
                <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">
                    Stake Amount
                    @if ($plan->amount_type === 'fixed')
                        (Fixed at {{ formatPrice($plan->fixed_amount) }})
                    @else
                        ({{ formatPrice($plan->min_amount) }}@if($plan->max_amount) &ndash; {{ formatPrice($plan->max_amount) }}@endif)
                    @endif
                </label>
                <input type="number" step="0.01" name="amount"
                       value="{{ $plan->amount_type === 'fixed' ? $plan->fixed_amount : old('amount') }}"
                       {{ $plan->amount_type === 'fixed' ? 'readonly' : '' }}
                       class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-lg font-bold text-white mb-6 focus:outline-none focus:border-[#4f8ef7]" required>

                <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-3 rounded-lg">Subscribe to {{ $plan->name }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
