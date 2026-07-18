@extends('layouts.desktop.trading')

@section('title', 'Neural Reserve')

@section('content')
<style>
    .reserve-shell { width: 100%; max-width: 1200px; margin: 0 auto; padding: 40px 24px; }
    .plan-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 32px; transition: 0.3s; }
    .plan-card:hover { transform: translateY(-6px); border-color: #3b82f6; }
    .btn-sync { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 14px 20px; border-radius: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; width: 100%; text-align: center; display: block; text-decoration: none; }
    @media (max-width: 768px) { .reserve-shell { padding: 20px; } }
</style>

<div class="reserve-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <div>
            <h1 style="font-size: 34px; font-weight: 950; margin: 0; letter-spacing: -2px; color:#fff;">Neural <span style="color:#3b82f6;">Reserve</span></h1>
            <p style="color: #64748b; margin-top: 8px;">Stake capital into automated yield nodes.</p>
        </div>
        <a href="{{ route('plans.subscriptions') }}" style="color:#3b82f6; font-weight:700; font-size:14px; text-decoration:none;">My Subscriptions &rarr;</a>
    </div>

    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px; margin-bottom:20px;">&#9989; {{ session('success') }}</div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        @forelse ($plans as $plan)
            <div class="plan-card">
                <div style="width: 40px; height: 5px; background: {{ $plan->badge_color ?? '#3b82f6' }}; border-radius: 10px; margin-bottom: 18px;"></div>
                <h3 style="font-size: 20px; font-weight: 900; color:#fff; margin:0 0 4px 0;">{{ $plan->name }}</h3>
                <p style="font-size:11px; font-weight:800; color:#3b82f6; text-transform:uppercase; letter-spacing:1px;">{{ $plan->amount_type === 'fixed' ? 'Fixed' : 'Range' }} Allocation</p>

                <div style="margin: 20px 0; font-size: 26px; font-weight: 950; color:#fff;">
                    @if ($plan->amount_type === 'fixed')
                        {{ formatPrice($plan->fixed_amount) }}
                    @else
                        {{ formatPrice($plan->min_amount) }}@if($plan->max_amount)<span style="font-size:14px; color:#94a3b8;"> &ndash; {{ formatPrice($plan->max_amount) }}</span>@endif
                    @endif
                </div>

                <div style="background: rgba(0,0,0,0.3); border-radius:16px; padding:16px; margin-bottom:20px; font-size:13px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;"><span style="color:#64748b;">Yield</span><span style="color:#10b981; font-weight:800;">{{ $plan->roi_percentage }}%</span></div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;"><span style="color:#64748b;">Duration</span><span style="color:#fff; font-weight:700;">{{ $plan->duration_days }} days</span></div>
                    <div style="display:flex; justify-content:space-between;"><span style="color:#64748b;">Capital</span><span style="color:#fff; font-weight:700;">{{ $plan->capital_lock ? 'Locked' : 'Flexible' }}</span></div>
                </div>

                <a href="{{ route('plans.show', $plan) }}" class="btn-sync">View &amp; Subscribe</a>
            </div>
        @empty
            <div style="grid-column: 1/-1; padding: 50px; background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.1); border-radius: 24px; text-align: center;">
                <p style="color: #94a3b8; font-weight: 700;">No active plans available right now.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
