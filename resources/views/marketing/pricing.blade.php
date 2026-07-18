@extends('layouts.marketing')

@section('title', 'Pricing')

@php
    // Illustrative tiers shown only until the investment-plans feature has real
    // rows in $plans — the fallback branch below is never reached once real
    // Plan rows exist. Field names match the Plan model exactly.
    $fallbackTiers = [
        (object) ['id' => null, 'name' => 'Starter', 'amount_type' => 'fixed', 'fixed_amount' => 100, 'min_amount' => null, 'max_amount' => null, 'roi_percentage' => 8, 'duration_days' => 30, 'capital_lock' => true, 'daily_task_limit' => 1, 'max_reinvest_count' => 2, 'fee_discount_percentage' => 0, 'badge_color' => '#2563eb'],
        (object) ['id' => null, 'name' => 'Growth', 'amount_type' => 'range', 'fixed_amount' => null, 'min_amount' => 1000, 'max_amount' => 9999, 'roi_percentage' => 14, 'duration_days' => 60, 'capital_lock' => true, 'daily_task_limit' => 2, 'max_reinvest_count' => 3, 'fee_discount_percentage' => 10, 'badge_color' => '#10b981'],
        (object) ['id' => null, 'name' => 'Elite', 'amount_type' => 'range', 'fixed_amount' => null, 'min_amount' => 10000, 'max_amount' => null, 'roi_percentage' => 22, 'duration_days' => 90, 'capital_lock' => false, 'daily_task_limit' => 3, 'max_reinvest_count' => 5, 'fee_discount_percentage' => 25, 'badge_color' => '#f59e0b'],
    ];
    $rows = $plans->isNotEmpty() ? $plans : collect($fallbackTiers);
@endphp

@section('content')
<style>
    .pricing-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 32px; padding: 35px; display: flex; flex-direction: column; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .pricing-card:hover { transform: translateY(-10px); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); }
    @media (max-width: 480px) { .pricing-card { padding: 25px; } }
</style>

<div class="pricing-container" style="width: 100%; max-width: 1400px; margin: 0 auto; padding: clamp(40px, 8vh, 100px) clamp(15px, 4vw, 40px); box-sizing: border-box;">

    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: clamp(30px, 5vw, 50px); font-weight: 950; color: #0f172a; margin-bottom: 20px; letter-spacing: -2px;">
            Investment <span style="color:#2563eb;">Plans</span>
        </h1>
        <p style="font-size: 17px; color: #64748b; max-width: 650px; margin: 0 auto; line-height: 1.6;">
            Commit funds to a fixed-term plan and earn a defined return, separate from active binary options trading.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 310px), 1fr)); gap: 30px; justify-content: center; align-items: stretch;">
        @if ($rows->isEmpty())
            <div style="grid-column: 1/-1; padding: 50px; background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 24px; text-align: center;">
                <p style="color: #94a3b8; font-weight: 700; font-size: 18px;">No investment plans are currently active.</p>
            </div>
        @else
            @foreach ($rows as $p)
                <div class="pricing-card">
                    <div style="width: 50px; height: 6px; background: {{ $p->badge_color ?? '#2563eb' }}; border-radius: 10px; margin-bottom: 20px;"></div>

                    <h3 style="font-size: 22px; font-weight: 900; color: #0f172a; margin: 0 0 5px 0;">{{ $p->name }}</h3>
                    <span style="font-size: 12px; font-weight: 800; color: #2563eb; text-transform: uppercase; letter-spacing: 1px;">
                        {{ $p->amount_type === 'fixed' ? 'Fixed' : 'Range' }} Allocation
                    </span>

                    <div style="margin: 25px 0;">
                        <div style="font-size: 38px; font-weight: 950; color: #0f172a; letter-spacing: -1.5px;">
                            @if ($p->amount_type === 'fixed')
                                ${{ number_format($p->fixed_amount) }}
                            @else
                                ${{ number_format($p->min_amount) }}
                                @if ($p->max_amount)
                                    <span style="font-size: 16px; color: #94a3b8;"> &ndash; ${{ number_format($p->max_amount) }}</span>
                                @else
                                    <span style="font-size: 16px; color: #94a3b8;">+</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div style="background: #f8fafc; border-radius: 20px; padding: 20px; margin-bottom: 25px; flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                            <span style="font-size: 13px; font-weight: 700; color: #64748b;">Yield Rate</span>
                            <span style="font-size: 16px; font-weight: 900; color: #10b981;">{{ number_format($p->roi_percentage, 2) }}% Total</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 13px; color: #64748b;">Maturity Period</span>
                            <span style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $p->duration_days }} Days</span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 13px; color: #64748b;">Capital Status</span>
                            <span style="font-size: 13px; font-weight: 800; color: {{ $p->capital_lock ? '#ef4444' : '#10b981' }};">
                                {{ $p->capital_lock ? 'Locked' : 'Fluid' }}
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="font-size: 13px; color: #64748b;">Tasks Required</span>
                            <span style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $p->daily_task_limit ?? 0 }} / Day</span>
                        </div>

                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 13px; color: #64748b;">Max Reinvests</span>
                            <span style="font-size: 13px; font-weight: 800; color: #0f172a;">{{ $p->max_reinvest_count ?? 0 }} Times</span>
                        </div>
                    </div>

                    <a href="{{ route('register') }}{{ $p->id ? '?plan=' . $p->id : '' }}"
                       style="display: block; width: 100%; padding: 18px; background: #0f172a; color: #ffffff; text-decoration: none; text-align: center; border-radius: 16px; font-weight: 800; font-size: 15px;">
                        Subscribe to This Plan
                    </a>

                    @if (($p->fee_discount_percentage ?? 0) > 0)
                        <div style="text-align: center; margin-top: 15px; font-size: 11px; font-weight: 700; color: #10b981; text-transform: uppercase;">
                            Includes {{ $p->fee_discount_percentage }}% Withdrawal Discount
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    @if ($plans->isEmpty())
        <p style="margin-top: 40px; text-align: center; font-size: 12px; color: #94a3b8;">Illustrative tiers &mdash; final plans are configured by the platform team.</p>
    @endif
</div>
@endsection
