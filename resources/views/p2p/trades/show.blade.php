@extends('layouts.desktop.trading')

@php
    $isBuyer = $trade->buyer_id === auth()->id();
    $isSeller = $trade->seller_id === auth()->id();
    $counterparty = $isBuyer ? $trade->seller : $trade->buyer;
@endphp

@section('title', 'P2P Trade #' . $trade->id)

@section('content')
<style>
    .p2p-shell { width: 100%; max-width: 700px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 32px; margin-bottom:20px; }
    .btn-go { border: none; padding: 14px 20px; border-radius: 12px; font-weight: 900; cursor: pointer; text-transform:uppercase; font-size:12px; letter-spacing:1px; }
    .btn-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); color:#fff; }
    .btn-green { background: linear-gradient(135deg, #059669, #10b981); color:#fff; }
    .btn-outline { background:none; border:1px solid rgba(255,255,255,0.15); color:#fff; }
    .btn-danger { background:none; border:1px solid rgba(239,68,68,0.4); color:#f87171; }
</style>

<div class="p2p-shell">
    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px; margin-bottom:20px;">&#9989; {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif

    <div class="cyber-card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
            <div>
                <h2 style="color:#fff; font-weight:900; margin:0 0 6px 0;">Trade #{{ $trade->id }}</h2>
                <p style="color:#64748b; font-size:13px;">You are the {{ $isBuyer ? 'buyer' : 'seller' }} in this escrow.</p>
            </div>
            <span style="padding:8px 16px; border-radius:100px; font-size:11px; font-weight:900; text-transform:uppercase; background:rgba(59,130,246,0.1); color:#3b82f6;">{{ str_replace('_', ' ', $trade->status) }}</span>
        </div>

        <dl style="display:grid; grid-template-columns:1fr 1fr; gap:16px; font-size:13px;">
            <div><dt style="color:#64748b;">Amount</dt><dd style="color:#fff; font-weight:800; font-size:18px;">{{ formatPrice($trade->amount) }}</dd></div>
            <div><dt style="color:#64748b;">Fiat Value</dt><dd style="color:#fff; font-weight:800; font-size:18px;">{{ number_format($trade->fiat_amount, 2) }}</dd></div>
            <div><dt style="color:#64748b;">Counterparty</dt><dd style="color:#fff; font-weight:700;">{{ $counterparty->first_name ?? 'Trader' }} {{ $counterparty->last_name ?? '' }}</dd></div>
            <div><dt style="color:#64748b;">Deadline</dt><dd style="color:#fff; font-weight:700;">{{ $trade->payment_deadline->format('d M, H:i') }}</dd></div>
        </dl>

        @if ($trade->payment_proof_path)
            <div style="margin-top:20px;">
                <p style="color:#64748b; font-size:11px; text-transform:uppercase; font-weight:800; margin-bottom:10px;">Payment Proof</p>
                <a href="{{ Storage::url($trade->payment_proof_path) }}" target="_blank" style="display:block; border-radius:14px; overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
                    <img src="{{ Storage::url($trade->payment_proof_path) }}" style="width:100%;" alt="Payment proof">
                </a>
            </div>
        @endif
    </div>

    @if ($isBuyer && $trade->status === 'pending_payment')
        <div class="cyber-card">
            <h3 style="color:#fff; font-weight:800; margin:0 0 16px 0;">Upload Payment Proof</h3>
            <form method="POST" action="{{ route('p2p-trades.pay', $trade) }}" enctype="multipart/form-data">
                @csrf
                <input type="file" name="payment_proof" accept="image/*" class="brand-input-dark" required style="margin-bottom:14px;">
                <button type="submit" class="btn-go btn-blue" style="width:100%;">I've Paid</button>
            </form>
        </div>
    @endif

    @if ($isSeller && $trade->status === 'paid')
        <div class="cyber-card">
            <h3 style="color:#fff; font-weight:800; margin:0 0 16px 0;">Release Escrow</h3>
            <p style="color:#94a3b8; font-size:13px; margin-bottom:16px;">Confirm you've received payment before releasing funds to the buyer.</p>
            <form method="POST" action="{{ route('p2p-trades.release', $trade) }}">
                @csrf
                <button type="submit" class="btn-go btn-green" style="width:100%;">Confirm Receipt &amp; Release</button>
            </form>
        </div>
    @endif

    @if (in_array($trade->status, ['pending_payment', 'paid']))
        <div class="cyber-card">
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                @if ($trade->status === 'pending_payment')
                    <form method="POST" action="{{ route('p2p-trades.cancel', $trade) }}">
                        @csrf
                        <button type="submit" class="btn-go btn-outline">Cancel Trade</button>
                    </form>
                @endif
                <form method="POST" action="{{ route('p2p-trades.dispute', $trade) }}" x-data="{ open: false }" @submit="if(!open){$event.preventDefault(); open = true}">
                    @csrf
                    <textarea x-show="open" name="reason" placeholder="Describe the issue..." class="brand-input-dark" style="margin-bottom:10px; width:280px;" required></textarea>
                    <button type="submit" class="btn-go btn-danger">Raise Dispute</button>
                </form>
            </div>
        </div>
    @endif

    @if ($trade->status === 'disputed')
        <div class="cyber-card">
            <p style="color:#f87171; font-weight:700; margin-bottom:10px;">Dispute raised {{ $trade->disputed_at->diffForHumans() }}</p>
            <p style="color:#94a3b8; font-size:13px; margin-bottom:16px;">{{ $trade->dispute_reason }}</p>
            @if ($trade->disputed_by === auth()->id())
                <form method="POST" action="{{ route('p2p-trades.dispute.undo', $trade) }}">
                    @csrf
                    <button type="submit" class="btn-go btn-outline">Withdraw Dispute</button>
                </form>
            @endif
        </div>
    @endif
</div>
@endsection
