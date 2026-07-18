@extends('layouts.desktop.trading')

@section('title', 'Identity Vault')

@section('content')
<style>
    .omni-profile-shell { width: 100%; max-width: 900px; margin: 0 auto; padding: 40px 24px; display: flex; flex-direction: column; gap: 30px; }
    .identity-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 35px; padding: 40px; }
    .btn-sync { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 18px 35px; border-radius: 18px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-flex; }
    @media (max-width: 768px) { .omni-profile-shell { padding: 20px; } .identity-card { padding: 30px 20px; border-radius: 25px; } }
</style>

<div class="omni-profile-shell">
    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px;">&#9989; {{ session('success') }}</div>
    @endif

    <div class="identity-card">
        <h2 style="margin: 0 0 10px 0; font-weight: 950; font-size: 26px; color: #fff; letter-spacing: -1px;">Identity Vault</h2>
        <p style="color:#64748b; font-size:14px; margin-bottom:30px;">KYC compliance status for your account.</p>

        @if (!$kyc)
            <div style="padding: 10px 20px; background:rgba(255,255,255,0.03); border-radius:100px; color:#94a3b8; font-size:11px; font-weight:800; text-transform:uppercase; display:inline-block; margin-bottom:20px;">
                &#9679; Unverified
            </div>
            <p style="color:#94a3b8; font-size:14px; margin-bottom:25px;">You have not yet submitted identity documents. Verification is required before withdrawals are unlocked.</p>
            <a href="{{ route('kyc.create') }}" class="btn-sync">Start Verification</a>
        @elseif ($kyc->status === 'pending')
            <div style="padding: 10px 20px; background:rgba(245,158,11,0.1); border-radius:100px; color:#fbbf24; font-size:11px; font-weight:800; text-transform:uppercase; display:inline-block; margin-bottom:20px;">
                &#9679; Awaiting Sync
            </div>
            <p style="color:#94a3b8; font-size:14px;">Your documents were submitted on {{ $kyc->submitted_at->format('d M, Y H:i') }} and are pending review.</p>
        @elseif ($kyc->status === 'verified')
            <div style="padding: 10px 20px; background:rgba(16,185,129,0.1); border-radius:100px; color:#10b981; font-size:11px; font-weight:800; text-transform:uppercase; display:inline-block; margin-bottom:20px;">
                &#9679; Verified
            </div>
            <p style="color:#94a3b8; font-size:14px;">Your account is fully verified. Withdrawals are unlocked.</p>
        @else
            <div style="padding: 10px 20px; background:rgba(239,68,68,0.1); border-radius:100px; color:#f87171; font-size:11px; font-weight:800; text-transform:uppercase; display:inline-block; margin-bottom:20px;">
                &#9679; Rejected
            </div>
            <p style="color:#94a3b8; font-size:14px; margin-bottom:15px;">Reason: {{ $kyc->rejection_reason }}</p>
            <a href="{{ route('kyc.create') }}" class="btn-sync">Resubmit Documents</a>
        @endif
    </div>
</div>
@endsection
