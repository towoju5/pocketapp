@extends('layouts.marketing')

@php $brand = config('app.name'); @endphp

@section('title', 'About ' . $brand)

@section('content')
<div class="hero-section" style="padding: 120px 24px; background: linear-gradient(135deg, #020617 0%, #0f172a 100%); color: #fff; text-align: center;">
    <h1 style="font-size: 56px; font-weight: 900; margin-bottom: 24px; letter-spacing: -0.03em; color: #fff;">About {{ $brand }}</h1>
    <p style="font-size: 22px; color: #94a3b8; max-width: 800px; margin: 0 auto; line-height: 1.6; font-weight:300;">
        Making binary options trading fast, transparent, and accessible to everyone.
    </p>
</div>

<div style="padding-top: 80px; padding-bottom: 80px;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 24px;">

        <h2 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 24px; letter-spacing:-0.02em;">Our Mission</h2>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
            Trading shouldn't require a finance degree or a trading desk. {{ $brand }} was built to strip away unnecessary complexity: pick an asset, choose a direction, set an expiry, and know your payout before you commit. We believe traders deserve clear pricing, fast execution, and honest information about the risks involved — not hidden fees or confusing jargon.
        </p>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
            Every account starts with a free demo wallet, so new traders can learn how the platform works — reading charts, placing trades, managing risk — without spending a cent. When you're ready, switching to real trading takes seconds.
        </p>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 48px;">
            {{ $brand }} is not a bank and does not offer investment advice. We provide the platform, the market data, and the tools; the trading decisions are always yours.
        </p>

        <h2 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 24px; letter-spacing:-0.02em;">How the Platform Works</h2>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
            At the core of {{ $brand }} is a live pricing engine that streams real-time quotes for currency pairs, cryptocurrencies, commodities, stocks, and indices — including OTC assets that stay tradable even when traditional markets are closed. Every trade you place is matched against the live price at the moment of execution, and settled automatically the instant your expiry is reached.
        </p>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
            To keep the platform fair for everyone, deposits and withdrawals run through identity verification (KYC) and encrypted wallet balances. Signals — trade ideas generated from live market analysis — are available to every trader, and can be copied into a trade with a single click.
        </p>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 48px;">
            Traders who want to compete can join tournaments with real prize pools, or explore Express Trading for faster-paced sessions with payout multipliers.
        </p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 40px; margin-bottom: 48px;">
            <h3 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">What We Stand For</h3>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 24px; display: flex; align-items: flex-start; gap: 16px;">
                    <span style="font-size: 28px; background: #e0f2fe; padding: 12px; border-radius: 12px;">&#128737;&#65039;</span>
                    <div>
                        <strong style="color: #0f172a; display:block; font-size: 18px; margin-bottom: 8px;">Transparent Pricing</strong>
                        <span style="color: #475569; font-size: 15px; line-height: 1.6; display:block;">Every trade shows its payout percentage up front. No hidden spreads, no surprise deductions — what you see before you trade is what you get.</span>
                    </div>
                </li>
                <li style="margin-bottom: 24px; display: flex; align-items: flex-start; gap: 16px;">
                    <span style="font-size: 28px; background: #fef3c7; padding: 12px; border-radius: 12px;">&#128200;</span>
                    <div>
                        <strong style="color: #0f172a; display:block; font-size: 18px; margin-bottom: 8px;">Real Market Data</strong>
                        <span style="color: #475569; font-size: 15px; line-height: 1.6; display:block;">Prices are streamed live and settlements are based on actual market movement — not simulated or artificially delayed feeds.</span>
                    </div>
                </li>
                <li style="display: flex; align-items: flex-start; gap: 16px;">
                    <span style="font-size: 28px; background: #dce1e5; padding: 12px; border-radius: 12px;">&#9881;&#65039;</span>
                    <div>
                        <strong style="color: #0f172a; display:block; font-size: 18px; margin-bottom: 8px;">Account Security</strong>
                        <span style="color: #475569; font-size: 15px; line-height: 1.6; display:block;">Encrypted wallets, identity-verified withdrawals, and optional two-factor authentication keep your funds and your account protected.</span>
                    </div>
                </li>
            </ul>
        </div>

        <h2 style="font-size: 36px; font-weight: 800; color: #0f172a; margin-bottom: 24px; letter-spacing:-0.02em;">Trade Responsibly</h2>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
            Binary options trading carries real risk — a losing trade means your full stake, not a partial loss. We encourage every trader to start on a demo account, trade with money you can afford to lose, and use the risk disclosure and terms available throughout the platform.
        </p>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; border-left: 4px solid #2563eb; padding-left: 16px; margin-left: 16px; font-style: italic;">
            "Our job is to give you the clearest, fastest platform to act on your own market view — the decisions are always yours."
        </p>
        <p style="font-size: 17px; color: #475569; line-height: 1.8; margin-top: 32px;">
            We invite you to create an account, try the free demo wallet, and see the platform for yourself.
        </p>
    </div>
</div>
@endsection
