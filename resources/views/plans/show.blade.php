@extends('layouts.desktop.trading')

@section('title', $plan->name)

@section('content')
<style>
    .reserve-shell { width: 100%; max-width: 700px; margin: 0 auto; padding: 40px 24px; }
    .plan-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 36px; }
    .vault-input { width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 16px; border-radius: 15px; color: #fff; font-size: 20px; font-weight: 800; outline: none; }
    .vault-input:focus { border-color: #3b82f6; }
    .btn-sync { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 18px; border-radius: 16px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; width: 100%; }
</style>

<div class="reserve-shell">
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif
    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif

    <div class="plan-card">
        <div style="width: 40px; height: 5px; background: {{ $plan->badge_color ?? '#3b82f6' }}; border-radius: 10px; margin-bottom: 18px;"></div>
        <h1 style="font-size: 28px; font-weight: 950; color:#fff; margin:0 0 6px 0;">{{ $plan->name }}</h1>
        <p style="color:#64748b; margin-bottom:25px;">{{ $plan->roi_percentage }}% yield over {{ $plan->duration_days }} days &bull; {{ $plan->capital_lock ? 'Capital locked' : 'Flexible capital' }}</p>

        <form method="POST" action="{{ route('plans.subscribe', $plan) }}">
            @csrf
            <label style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:8px;">
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
                   class="vault-input" style="margin-bottom: 25px;" required>

            <button type="submit" class="btn-sync">Subscribe to {{ $plan->name }}</button>
        </form>
    </div>
</div>
@endsection
