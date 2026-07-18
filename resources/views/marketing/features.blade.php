@extends('layouts.marketing')

@php $brand = config('app.name'); @endphp

@section('title', 'Features')

@section('content')
<style>
    :root { --p-blue: #2563eb; --p-dark: #0f172a; }
    .omni-shell { width: 100%; max-width: 1300px; margin: 0 auto; padding: clamp(40px, 8vw, 100px) 24px; }
    .bento-matrix { display: grid; grid-template-columns: repeat(12, 1fr); gap: 24px; margin-top: 60px; }
    .feature-node { background: #ffffff; border: 1px solid rgba(0,0,0,0.06); border-radius: 30px; padding: 40px; transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); display: flex; flex-direction: column; justify-content: flex-start; }
    .feature-node:hover { transform: translateY(-10px); border-color: var(--p-blue); box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.12); }
    .span-7 { grid-column: span 7; } .span-5 { grid-column: span 5; } .span-4 { grid-column: span 4; }
    .span-8 { grid-column: span 8; } .span-6 { grid-column: span 6; } .span-12 { grid-column: span 12; }
    .node-icon { width: 54px; height: 54px; background: #f8fafc; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 25px; box-shadow: 0 10px 20px rgba(0,0,0,0.03); }
    .node-title { font-size: 22px; font-weight: 900; color: var(--p-dark); margin-bottom: 12px; letter-spacing: -1px; }
    .node-text { color: #64748b; font-size: 15px; line-height: 1.7; margin: 0; }
    @media (max-width: 1024px) { .span-7, .span-5, .span-4, .span-8, .span-6 { grid-column: span 12; } .bento-matrix { gap: 20px; } }
    @media (max-width: 600px) { .omni-shell { padding: 40px 16px; } .feature-node { padding: 30px 24px; border-radius: 24px; } }
</style>

<div class="omni-shell">
    <div style="text-align: center; max-width: 800px; margin: 0 auto;">
        <div style="display:inline-block; padding: 8px 16px; background: rgba(37,99,235,0.08); color: var(--p-blue); border-radius: 100px; font-size: 12px; font-weight: 800; margin-bottom: 20px; letter-spacing: 1px;">
            TRADING PLATFORM
        </div>
        <h1 style="font-size: clamp(32px, 5vw, 55px); font-weight: 950; color: var(--p-dark); margin-bottom: 24px; letter-spacing: -3px; line-height: 1;">Built for Traders.</h1>
        <p style="font-size: 18px; color: #64748b; line-height: 1.6;">
            A closer look at the tools driving <b>{{ $brand }}</b>'s trading experience — from live charts to secure withdrawals.
        </p>
    </div>

    <div class="bento-matrix">
        <div class="feature-node span-7">
            <div class="node-icon">&#128272;</div>
            <h3 class="node-title">Secure Wallets &amp; Escrow-Grade P2P</h3>
            <p class="node-text">Wallet balances are encrypted, and our P2P marketplace locks funds until both sides confirm the trade, keeping peer-to-peer deposits and transfers safe from fraud.</p>
        </div>

        <div class="feature-node span-5">
            <div class="node-icon">&#9889;</div>
            <h3 class="node-title">Fast Trade Execution</h3>
            <p class="node-text">Trades are placed and confirmed in real time against live streaming prices, so you're never left waiting to act on a move.</p>
        </div>

        <div class="feature-node span-4">
            <div class="node-icon">&#128200;</div>
            <h3 class="node-title">Live Trading Signals</h3>
            <p class="node-text">Signals are generated from real-time market analysis and can be copied into a trade with a single click, right from the Signals page.</p>
        </div>

        <div class="feature-node span-8" style="background: var(--p-dark); color: #fff;">
            <div class="node-icon" style="background: rgba(255,255,255,0.1);">&#128752;</div>
            <h3 class="node-title" style="color: #fff;">Markets That Never Sleep</h3>
            <p class="node-text" style="color: #94a3b8;">Beyond standard forex, crypto, stock, and index pairs, {{ $brand }} offers OTC assets priced continuously by liquidity providers, so you can trade nights, weekends, and holidays.</p>
        </div>

        <div class="feature-node span-6">
            <div class="node-icon">&#128373;&#65039;</div>
            <h3 class="node-title">Identity-Verified Withdrawals</h3>
            <p class="node-text">KYC verification and device-level checks protect your account, keeping withdrawals restricted to you and only you.</p>
        </div>

        <div class="feature-node span-6">
            <div class="node-icon">&#9878;&#65039;</div>
            <h3 class="node-title">Fair Dispute Resolution</h3>
            <p class="node-text">P2P and support disputes are reviewed by our team through a clear, auditable process, so disagreements get resolved fairly.</p>
        </div>

        <div class="feature-node span-5">
            <div class="node-icon">&#128142;</div>
            <h3 class="node-title">Investment Plans</h3>
            <p class="node-text">Prefer a fixed-term approach? Investment Plans let you commit funds toward a defined return, separate from active trading.</p>
        </div>

        <div class="feature-node span-7">
            <div class="node-icon">&#128241;</div>
            <h3 class="node-title">Trade From Any Device</h3>
            <p class="node-text">The {{ $brand }} interface is fully responsive — manage your wallet, review trade history, and place trades smoothly on desktop or mobile.</p>
        </div>
    </div>
</div>
@endsection
