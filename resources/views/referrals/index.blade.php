@extends('layouts.desktop.trading')

@section('title', 'Affiliate Matrix')

@section('content')
<style>
    .ref-shell { width: 100%; max-width: 1100px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .tier-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 20px; text-align: center; }
</style>

<div class="ref-shell">
    <h1 style="font-size: 32px; font-weight: 950; margin: 0 0 8px 0; color:#fff;">Affiliate <span style="color:#3b82f6;">Matrix</span></h1>
    <p style="color: #64748b; margin-bottom:30px;">Earn commission across 3 levels of your referral network.</p>

    <div class="cyber-card" style="margin-bottom:24px;">
        <p style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-bottom:10px;">Your Referral Link</p>
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <code id="refLink" style="background:rgba(0,0,0,0.3); padding:14px 18px; border-radius:12px; color:#3b82f6; font-size:14px; flex:1; min-width:200px; overflow-wrap:anywhere;">{{ url('/register?ref=' . $user->referral_code) }}</code>
            <button onclick="navigator.clipboard.writeText(document.getElementById('refLink').innerText)" style="background:linear-gradient(135deg,#2563eb,#3b82f6); color:#fff; border:none; padding:14px 22px; border-radius:12px; font-weight:800; cursor:pointer;">Copy</button>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:24px;">
        <div class="tier-card"><div style="font-size:28px; font-weight:950; color:#fff;">{{ $directCount }}</div><div style="font-size:11px; color:#64748b; text-transform:uppercase; margin-top:6px;">Direct (L1)</div></div>
        <div class="tier-card"><div style="font-size:28px; font-weight:950; color:#fff;">{{ $level2Count }}</div><div style="font-size:11px; color:#64748b; text-transform:uppercase; margin-top:6px;">Level 2</div></div>
        <div class="tier-card"><div style="font-size:28px; font-weight:950; color:#fff;">{{ $level3Count }}</div><div style="font-size:11px; color:#64748b; text-transform:uppercase; margin-top:6px;">Level 3</div></div>
        <div class="tier-card" style="border-color: rgba(16,185,129,0.3);"><div style="font-size:28px; font-weight:950; color:#10b981;">{{ formatPrice($totalEarned) }}</div><div style="font-size:11px; color:#64748b; text-transform:uppercase; margin-top:6px;">Total Earned</div></div>
    </div>

    <div class="cyber-card" style="margin-bottom:24px;">
        <h3 style="color:#fff; font-weight:800; margin:0 0 16px 0;">Commission History</h3>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse: separate; border-spacing: 0 10px; color:#fff;">
                <thead>
                    <tr style="text-align:left; font-size:11px; color:#64748b; text-transform:uppercase;">
                        <th style="padding:0 16px;">From</th><th style="padding:0 16px;">Level</th><th style="padding:0 16px;">Activity</th><th style="padding:0 16px;">Commission</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commissions as $c)
                        <tr style="background:rgba(255,255,255,0.02);">
                            <td style="padding:14px 16px; border-radius:14px 0 0 14px;">{{ $c->referredUser->first_name ?? 'Trader' }}</td>
                            <td style="padding:14px 16px;">L{{ $c->level }}</td>
                            <td style="padding:14px 16px; text-transform:capitalize;">{{ $c->activity_type }}</td>
                            <td style="padding:14px 16px; border-radius:0 14px 14px 0; color:#10b981; font-weight:700;">{{ formatPrice($c->commission_amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding:40px; color:#475569; font-weight:800;">No commissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $commissions->links() }}</div>
    </div>

    <div class="cyber-card">
        <h3 style="color:#fff; font-weight:800; margin:0 0 16px 0;">Invite Registry</h3>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @forelse ($directReferrals as $ref)
                <div style="display:flex; justify-content:space-between; background:rgba(255,255,255,0.02); padding:14px 18px; border-radius:14px;">
                    <span style="color:#fff;">{{ $ref->first_name }} {{ $ref->last_name }}</span>
                    <span style="color:#64748b; font-size:13px;">{{ $ref->created_at->format('d M, Y') }}</span>
                </div>
            @empty
                <p style="color:#94a3b8; text-align:center; padding:20px;">No direct referrals yet. Share your link above!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
