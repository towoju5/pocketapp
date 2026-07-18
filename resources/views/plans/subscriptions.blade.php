@extends('layouts.desktop.trading')

@section('title', 'My Plan Subscriptions')

@section('content')
<style>
    .reserve-shell { width: 100%; max-width: 1100px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 30px; }
    .status-badge { padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 900; text-transform: uppercase; }
    .status-active { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .status-matured { background: rgba(16,185,129,0.1); color: #10b981; }
    .status-cancelled { background: rgba(148,163,184,0.1); color: #94a3b8; }
</style>

<div class="reserve-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <div>
            <h1 style="font-size: 30px; font-weight: 950; margin: 0; color:#fff;">Neural Asset Registry</h1>
            <p style="color: #64748b; margin-top: 8px;">Your active and matured plan subscriptions.</p>
        </div>
        <a href="{{ route('plans.index') }}" style="color:#3b82f6; font-weight:700; font-size:14px; text-decoration:none;">&larr; Browse Plans</a>
    </div>

    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px; margin-bottom:20px;">&#9989; {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif

    <div class="cyber-card">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse: separate; border-spacing: 0 10px; color:#fff;">
                <thead>
                    <tr style="text-align:left; font-size:11px; color:#64748b; text-transform:uppercase;">
                        <th style="padding:0 20px;">Plan</th>
                        <th style="padding:0 20px;">Stake</th>
                        <th style="padding:0 20px;">Projected Payout</th>
                        <th style="padding:0 20px;">Matures</th>
                        <th style="padding:0 20px;">Status</th>
                        <th style="padding:0 20px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subscriptions as $sub)
                        <tr style="background:rgba(255,255,255,0.02);">
                            <td style="padding:16px 20px; border-radius:16px 0 0 16px; font-weight:700;">{{ $sub->plan->name ?? 'Deleted plan' }}</td>
                            <td style="padding:16px 20px;">{{ formatPrice($sub->stake_amount) }}</td>
                            <td style="padding:16px 20px; color:#10b981; font-weight:700;">{{ formatPrice($sub->expected_payout) }}</td>
                            <td style="padding:16px 20px; font-size:13px; color:#94a3b8;">
                                @if ($sub->status === 'active')
                                    {{ $sub->matures_at->diffForHumans() }}
                                @else
                                    {{ $sub->matures_at->format('d M, Y') }}
                                @endif
                            </td>
                            <td style="padding:16px 20px;"><span class="status-badge status-{{ $sub->status }}">{{ $sub->status }}</span></td>
                            <td style="padding:16px 20px; border-radius:0 16px 16px 0; text-align:right;">
                                @if ($sub->status === 'matured' && $sub->plan && (is_null($sub->plan->max_reinvest_count) || $sub->reinvest_count < $sub->plan->max_reinvest_count))
                                    <form method="POST" action="{{ route('plans.reinvest', $sub) }}">
                                        @csrf
                                        <button type="submit" style="background:none; border:1px solid #3b82f6; color:#3b82f6; font-size:11px; font-weight:800; text-transform:uppercase; padding:8px 14px; border-radius:10px; cursor:pointer;">Reinvest</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center; padding:50px; color:#475569; font-weight:800;">[ REGISTRY VACANT ]</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">{{ $subscriptions->links() }}</div>
    </div>
</div>
@endsection
