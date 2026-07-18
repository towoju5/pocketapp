@extends('layouts.desktop.trading')

@section('title', 'Withdraw Funds')

@section('content')
<style>
    .diamond-shell { width: 100%; max-width: 1200px; margin: 0 auto; padding: 40px 24px; display: flex; flex-direction: column; gap: 30px; overflow-x: hidden; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 35px; padding: 40px; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); width: 100%; }
    .cyber-card:hover { transform: translateY(-8px); border-color: #3b82f6; box-shadow: 0 40px 80px -20px rgba(0,0,0,0.6); }
    .input-field { width: 100%; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 18px; border-radius: 15px; color: #fff; font-size: 16px; transition: 0.3s; margin-top: 8px; }
    .input-field:focus { border-color: #3b82f6; outline: none; background: rgba(0,0,0,0.5); }
    .btn-submit { width: 100%; padding: 22px; border-radius: 20px; border: none; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: 0.3s; margin-top: 10px; }
    .btn-withdraw { background: linear-gradient(135deg, #d97706, #fbbf24); color: #fff; box-shadow: 0 10px 25px rgba(251, 191, 36, 0.2); }
    .btn-submit:active { transform: scale(0.96); }
    @media (max-width: 768px) { .diamond-shell { padding: 20px 20px; gap: 20px; } .cyber-card { padding: 30px 20px; border-radius: 25px; } }
</style>

<div class="diamond-shell">
    <div>
        @include('partials.finance-header')
    </div>

    <div>
        <h1 style="font-size: 38px; font-weight: 950; margin: 0; letter-spacing: -2px; color:#fff;">Withdraw <span style="color:#3b82f6;">Funds</span></h1>
        <p style="color: #64748b; margin-top: 8px; font-size: 16px;">Request a withdrawal from your trading account</p>
    </div>

    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px;">&#9989; {{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px;">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    @if(!($kycVerified ?? false))
        <div style="background:rgba(217,119,6,0.1); border-left:5px solid #d97706; color:#fbbf24; padding:20px; border-radius:15px; max-width:600px;">
            &#9888;&#65039; Identity verification is required before you can withdraw.
            <a href="{{ route('kyc.show') }}" style="color:#fff; text-decoration:underline;">Verify identity</a>
        </div>
    @endif

    <div class="cyber-card" style="border-top: 4px solid #10b981; max-width: 600px;">
        <h2 style="font-size: 22px; margin: 0 0 25px 0; color:#fff;">Withdraw Funds</h2>
        <form method="POST" action="{{ route('payout.create') }}">
            @csrf

            <label style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase;">Method</label>
            <select name="payment_method" class="input-field">
                <option value="USDT">USDT (TRC20)</option>
                <option value="Bank">Bank Transfer</option>
            </select>

            <label style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-top:20px; display:block;">Amount</label>
            <input type="number" step="0.01" name="amount" class="input-field" style="font-size:24px; font-weight:900; color:#3b82f6;" placeholder="0.00" value="{{ old('amount') }}" required>

            <label style="font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; margin-top:20px; display:block;">USDT (TRC20) Address</label>
            <input type="text" name="address" class="input-field" placeholder="Txxxxxxxxxxxxxxxxxxxxxxxxxxxx" value="{{ old('address') }}" required>

            <button type="submit" class="btn-submit btn-withdraw">Submit Withdrawal</button>
        </form>
    </div>
</div>
@endsection
