@extends('layouts.marketing')

@php
    $brand = config('app.name');
    $cUsers = \App\Models\User::count() ?: 89420;
    $vTx = 12400000;
    $cDuties = 1200;
    $totalPayouts = 8500000;
@endphp

@section('title', $brand)

@section('content')
<style>
    :root { --p-blue: #2563eb; --p-dark: #0f172a; --glass: rgba(255, 255, 255, 0.9); --neon: #00FFA3; }
    .omni-hero {
        padding: clamp(100px, 15vh, 200px) 24px;
        background: radial-gradient(circle at top right, #1e293b, #0f172a);
        text-align: center; position: relative; color: #fff; overflow: hidden;
    }
    .hero-glitch { font-size: clamp(32px, 8vw, 75px); font-weight: 950; letter-spacing: -4px; line-height: 1; margin-bottom: 30px; }
    .hero-sub { font-size: clamp(16px, 2vw, 20px); color: #94a3b8; max-width: 800px; margin: 0 auto 50px; line-height: 1.6; }

    .btn-nexus {
        display: inline-flex; align-items: center; gap: 10px; padding: 18px 40px;
        border-radius: 15px; font-weight: 800; text-decoration: none; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-glow { background: var(--p-blue); color: #fff; box-shadow: 0 15px 30px rgba(37, 99, 235, 0.3); }
    .btn-outline { background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px); }
    .btn-nexus:hover { transform: translateY(-5px); filter: brightness(1.2); }

    .data-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px; max-width: 1300px; margin: -60px auto 100px; padding: 0 24px; position: relative; z-index: 100;
    }
    .data-card {
        background: #fff; padding: 35px; border-radius: 25px; text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; transition: 0.3s;
    }
    .data-card:hover { transform: translateY(-10px); border-color: var(--p-blue); }
    .data-val { font-size: 38px; font-weight: 900; color: var(--p-dark); letter-spacing: -1px; margin-bottom: 5px; }
    .data-lab { font-size: 11px; font-weight: 900; text-transform: uppercase; color: #64748b; letter-spacing: 2px; }

    .section-shell { padding: 100px 24px; max-width: 1200px; margin: 0 auto; }
    .h2-nexus { font-size: clamp(28px, 4vw, 45px); font-weight: 900; letter-spacing: -2px; margin-bottom: 20px; }
    .p-nexus { color: #64748b; font-size: 17px; line-height: 1.8; margin-bottom: 60px; }

    .bento-features { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
    .bento-item { background: #fff; padding: 40px; border-radius: 30px; border: 1px solid #e2e8f0; transition: 0.4s; }
    .bento-item:hover { background: var(--p-dark); color: #fff; }
    .bento-item:hover p { color: #94a3b8; }

    .tier-card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; padding: 30px; text-align: center; }
    .tier-badge { display:inline-block; font-size:11px; font-weight:900; letter-spacing:1px; color:var(--p-blue); background:rgba(37,99,235,0.15); padding:6px 14px; border-radius:100px; margin-bottom:15px; }
    .tier-percentage { font-size: 42px; font-weight: 950; color: #fff; }
    .tier-label { font-weight: 800; color: #fff; margin: 10px 0; }
    .tier-desc { color: #94a3b8; font-size: 14px; line-height: 1.6; }

    .faq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 900px) { .faq-grid { grid-template-columns: 1fr; } }
    .faq-node { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
    .faq-trigger { width: 100%; background: none; border: none; padding: 20px; text-align: left; display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-weight: 700; color: var(--p-dark); font-size: 15px; }
    .faq-node.active .faq-trigger { color: var(--p-blue); }
    .faq-body { padding: 0 20px 20px 20px; color: #64748b; font-size: 14px; line-height: 1.6; }

    .marquee-wrapper { overflow: hidden; }
    .marquee-track { display: flex; gap: 60px; width: max-content; animation: marquee-scroll 40s linear infinite; }
    .marquee-wrapper:hover .marquee-track { animation-play-state: paused; }
    @keyframes marquee-scroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
    .partner-logo { display: flex; align-items: center; gap: 10px; color: #cbd5e1; font-weight: 700; font-size: 14px; white-space: nowrap; }

    .spec-list { display: flex; flex-direction: column; gap: 20px; margin-top: 30px; }
    .spec-item { display: flex; gap: 15px; align-items: flex-start; }
    .spec-check { width: 28px; height: 28px; border-radius: 50%; background: rgba(0,255,163,0.15); color: #00FFA3; display: flex; align-items: center; justify-content: center; font-weight: 900; flex-shrink: 0; }

    @media (max-width: 768px) {
        .data-grid { margin-top: -30px; }
        .section-shell { padding: 60px 20px; }
        .btn-nexus { width: 100%; justify-content: center; }
    }
</style>

<section class="omni-hero">
    <div class="section-shell">
        <div style="display:inline-block; padding: 8px 20px; background:rgba(37, 99, 235, 0.1); color:var(--p-blue); border-radius:100px; font-size:11px; font-weight:900; letter-spacing:2px; margin-bottom:30px; border: 1px solid rgba(37, 99, 235, 0.2);">
            TRADE FOREX, CRYPTO, STOCKS &amp; INDICES
        </div>
        <h1 class="hero-glitch">
            Trade Binary Options<br>
            <span style="color: #3b82f6; text-shadow: 0 0 20px rgba(59, 130, 246, 0.6), 0 0 40px rgba(59, 130, 246, 0.3); filter: brightness(1.4);">Fast.</span> Simple. Anywhere.
        </h1>
        <p class="hero-sub">Open an account with {{ $brand }} and trade live price movements on currencies, crypto, commodities and indices — with up to 92% payouts, real-time signals, and instant demo access to practice risk-free.</p>

        <div style="display:flex; gap:20px; justify-content:center; flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn-nexus btn-glow">&#128640; SIGN UP</a>
            <a href="{{ route('login') }}" class="btn-nexus btn-outline">&#128225; LOGIN</a>
        </div>
    </div>
</section>

<section class="data-grid">
    <div class="data-card">
        <div class="data-val">{{ number_format($cUsers) }}</div>
        <div class="data-lab">Active Traders</div>
    </div>
    <div class="data-card">
        <div class="data-val">${{ number_format($vTx) }}</div>
        <div class="data-lab">Volume Traded</div>
    </div>
    <div class="data-card">
        <div class="data-val" style="color:var(--p-blue);">{{ number_format($cDuties) }}</div>
        <div class="data-lab">Signals Delivered</div>
    </div>
    <div class="data-card">
        <div class="data-val" style="color:#10b981;">${{ number_format($totalPayouts) }}</div>
        <div class="data-lab">Payouts Dispatched</div>
    </div>
</section>

<section class="section-shell" style="text-align:center;">
    <h2 class="h2-nexus">Simple Trades. Transparent Payouts.</h2>
    <p class="p-nexus" style="max-width:800px; margin-left:auto; margin-right:auto;">{{ $brand }} makes binary options trading straightforward: pick an asset, choose a direction, set an expiry, and know your payout before you trade. No hidden spreads, no surprises.</p>

    <div class="bento-features">
        <div class="bento-item">
            <div style="font-size:40px; margin-bottom:20px;">&#128737;&#65039;</div>
            <h3 style="font-size:22px; font-weight:800; margin-bottom:15px;">Secure Wallet &amp; Withdrawals</h3>
            <p style="font-size:15px; line-height:1.6; color:#64748b;">Deposits and withdrawals are protected behind KYC verification and encrypted wallet balances, so your funds stay safe from the moment you top up.</p>
        </div>
        <div class="bento-item">
            <div style="font-size:40px; margin-bottom:20px;">&#9889;</div>
            <h3 style="font-size:22px; font-weight:800; margin-bottom:15px;">Live Charts, Real-Time Prices</h3>
            <p style="font-size:15px; line-height:1.6; color:#64748b;">Trade against a live streaming chart with candlestick, bar, line and Heikin Ashi views, across multiple timeframes down to 5 seconds.</p>
        </div>
        <div class="bento-item">
            <div style="font-size:40px; margin-bottom:20px;">&#128142;</div>
            <h3 style="font-size:22px; font-weight:800; margin-bottom:15px;">Up to 92% Payouts</h3>
            <p style="font-size:15px; line-height:1.6; color:#64748b;">Payout percentages vary by asset and are always shown before you trade, so you always know exactly what a winning trade returns.</p>
        </div>
    </div>
</section>

<section style="background: #0f172a; color: #fff; padding: 120px 24px;">
    <div class="section-shell">
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 60px; align-items: center;">
            <div>
                <h2 class="h2-nexus" style="color:#fff;">Built for Serious Traders.</h2>
                <p style="color:#94a3b8; font-size:18px; line-height:1.8; margin-bottom:30px;">Every {{ $brand }} account comes with a free demo wallet to practice strategies risk-free, alongside a real-money wallet for live trading once you're ready.</p>

                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:20px;">
                    <li style="display:flex; gap:15px; align-items:center;"><span style="color:var(--p-blue); font-weight:900;">[01]</span><span>Free Demo Account, No Deposit Required</span></li>
                    <li style="display:flex; gap:15px; align-items:center;"><span style="color:var(--p-blue); font-weight:900;">[02]</span><span>Encrypted Wallets &amp; KYC-Verified Withdrawals</span></li>
                    <li style="display:flex; gap:15px; align-items:center;"><span style="color:var(--p-blue); font-weight:900;">[03]</span><span>Live Trading Signals From Real Market Data</span></li>
                </ul>
            </div>
            <div style="background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(25px); padding: 35px; border-radius: 25px; border: 1px solid rgba(59, 130, 246, 0.3); box-shadow: 0 0 30px rgba(37, 99, 235, 0.15); max-width: 420px; margin: 0 auto;">
                <h3 style="color:#3b82f6; font-weight:900; margin-bottom:15px; font-family:monospace; font-size: 14px; letter-spacing: 1px;">// EUR/USD OTC</h3>
                <div style="font-family: monospace; color: #94a3b8; font-size: 12px; line-height: 1.8;">
                    &gt; Streaming live price feed...<br>
                    &gt; Timeframe: M1<br>
                    &gt; Payout: 92%<br>
                    &gt; Market: OPEN
                </div>
                <div style="margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 15px; display:flex; justify-content:space-between; align-items:center;">
                    <span style="color:#10b981; font-weight: 900; font-size: 10px; font-family: monospace; text-transform: uppercase;">&#9679; Market Open</span>
                    <span style="color:#3b82f6; font-family: monospace; font-size: 10px;">LIVE</span>
                </div>
                <a href="{{ route('register') }}" class="btn-nexus btn-glow" style="margin-top:25px; width:100%; text-decoration:none; display:block; text-align:center;">START TRADING</a>
            </div>
        </div>
    </div>
</section>

<section class="section-shell">
    <h2 class="h2-nexus" style="text-align:center;">Frequently Asked Questions</h2>
    <p class="p-nexus" style="text-align:center;">Everything you need to know before you place your first trade on {{ $brand }}.</p>

    @php
        $homeFaqs = [
            "What is binary options trading?" => "You predict whether an asset's price will be higher or lower than its current price when a set expiry time is reached. If you're right, you receive the payout shown before you traded.",
            "How are payouts calculated?" => "Each asset has its own payout percentage, shown on the trade panel before you confirm. A winning trade returns your stake plus that percentage in profit.",
            "Is my account secure?" => "Yes. Wallet balances are encrypted, withdrawals require identity verification (KYC), and optional two-factor authentication adds an extra layer of protection to your account.",
            "What are Signals?" => "Signals are trade ideas generated from live market analysis. You can view active signals and copy them directly into a trade with one click.",
            "Can I practice without risking money?" => "Yes. Every account includes a free demo wallet pre-loaded with virtual funds, so you can practice strategies with zero financial risk before switching to real trading.",
            "Which assets can I trade?" => "Currency pairs (including OTC pairs available on weekends), cryptocurrencies, commodities like gold and silver, stocks, and major indices.",
            "How do withdrawals work?" => "Once your identity is verified, you can request a withdrawal from your wallet. Requests are reviewed and processed to your chosen payment method.",
            "What is Express Trading?" => "Express trades let you trade with a payout multiplier on shorter expirations, for traders who want faster-paced sessions.",
            "How fast are trade expirations?" => "You can choose expirations as short as 5 seconds up to longer, multi-minute windows, depending on the asset and timeframe.",
            "Can I trade on weekends?" => "Yes, via OTC (over-the-counter) assets, which are priced continuously by liquidity providers even when traditional markets are closed.",
            "What happens if my prediction is wrong?" => "If the price closes against your prediction at expiry, the trade is recorded as a loss and your stake is not returned.",
            "Are there any trading fees?" => "There are no separate trading fees — the payout percentage shown before each trade already reflects the platform's margin.",
            "How does the referral program work?" => "Invite others to join {{ $brand }} and earn a commission based on their trading activity, credited directly to your wallet.",
            "How do I contact support?" => "Use the in-app Support Tickets section or the live chat to reach our support team any time.",
            "How do I recover my account?" => "If you lose access to your account, use the password reset flow from the login page, or contact support for identity-verified recovery.",
        ];
    @endphp

    <div class="faq-grid" x-data="{ open: null }">
        @foreach ($homeFaqs as $q => $a)
            <div class="faq-node" x-bind:class="open === {{ $loop->index }} ? 'active' : ''">
                <button type="button" class="faq-trigger" @click="open = (open === {{ $loop->index }} ? null : {{ $loop->index }})">
                    {{ $q }} <span>+</span>
                </button>
                <div class="faq-body" x-show="open === {{ $loop->index }}" x-collapse x-cloak>{{ $a }}</div>
            </div>
        @endforeach
    </div>
</section>

<section class="section-shell" style="text-align:center;">
    <div style="display:inline-block; padding: 6px 16px; background:rgba(37,99,235,0.1); color:var(--p-blue); border-radius:100px; font-size:11px; font-weight:900; letter-spacing:1px; margin-bottom:20px;">REFERRAL PROGRAM</div>
    <h2 class="h2-nexus">Earn by <span style="color:var(--p-blue)">Referring</span> Traders</h2>
    <p class="p-nexus" style="max-width:700px; margin-left:auto; margin-right:auto;">{{ $brand }}'s referral program rewards you for growing the community. Earn commission across three levels as your referrals trade.</p>

    <div style="background:#0f172a; border-radius:30px; padding:50px 30px; display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:24px;">
        <div class="tier-card"><div class="tier-badge">LEVEL 01</div><div class="tier-percentage">5%</div><div class="tier-label">Direct Referrals</div><p class="tier-desc">Earn commission on the trading activity of traders you refer directly.</p></div>
        <div class="tier-card"><div class="tier-badge">LEVEL 02</div><div class="tier-percentage">3%</div><div class="tier-label">Second-Level Referrals</div><p class="tier-desc">Earn a share of commission from traders referred by your direct referrals.</p></div>
        <div class="tier-card"><div class="tier-badge">LEVEL 03</div><div class="tier-percentage">1%</div><div class="tier-label">Third-Level Referrals</div><p class="tier-desc">Earn a residual share as your referral network grows three levels deep.</p></div>
    </div>
</section>

<section style="background:#020617; padding: 60px 0; overflow:hidden;">
    <p style="text-align:center; color:#94a3b8; font-size:12px; font-weight:900; letter-spacing:2px; margin-bottom:30px;">TRADABLE ASSETS &amp; MARKETS</p>
    <div class="marquee-wrapper">
        <div class="marquee-track">
            @foreach (['EUR/USD', 'GBP/USD', 'USD/JPY', 'BTC/USD', 'ETH/USD', 'Gold', 'Silver', 'Crude Oil', 'Apple', 'Tesla', 'Google', 'US 500', 'Nasdaq 100', 'UK 100', 'Germany 40', 'Japan 225'] as $partner)
                <div class="partner-logo">{{ $partner }}</div>
            @endforeach
            @foreach (['EUR/USD', 'GBP/USD', 'USD/JPY', 'BTC/USD', 'ETH/USD', 'Gold', 'Silver', 'Crude Oil', 'Apple', 'Tesla', 'Google', 'US 500', 'Nasdaq 100', 'UK 100', 'Germany 40', 'Japan 225'] as $partner)
                <div class="partner-logo">{{ $partner }}</div>
            @endforeach
        </div>
    </div>
</section>

<section style="background:#0f172a; color:#fff; padding: 120px 24px;">
    <div class="section-shell" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px,1fr)); gap:60px; align-items:center;">
        <div>
            <div style="display:inline-block; padding: 6px 16px; background:rgba(37,99,235,0.15); color:#3b82f6; border-radius:100px; font-size:11px; font-weight:900; letter-spacing:1px; margin-bottom:20px;">TRADING ENGINE</div>
            <h2 class="h2-nexus" style="color:#fff;">Fast, Reliable Trade Execution</h2>
            <p style="color:#94a3b8; font-size:17px; line-height:1.8; margin-bottom:30px;">{{ $brand }} streams live prices straight to your chart and confirms trade placement instantly, so you never miss a move in fast-moving markets.</p>

            <div style="display:flex; flex-direction:column; gap:20px;">
                <div style="display:flex; gap:15px; align-items:flex-start;">
                    <div style="font-size:22px;">&#9889;</div>
                    <div><div style="font-weight:800;">Instant Trade Confirmation</div><div style="color:#94a3b8; font-size:14px;">Trades are placed and confirmed in real time.</div></div>
                </div>
                <div style="display:flex; gap:15px; align-items:flex-start;">
                    <div style="font-size:22px;">&#128737;&#65039;</div>
                    <div><div style="font-weight:800;">Secure Wallets</div><div style="color:#94a3b8; font-size:14px;">Encrypted balances that keep your funds safe.</div></div>
                </div>
            </div>
        </div>
        <div style="display:flex; align-items:center; justify-content:center;">
            <div style="width:220px; height:220px; border-radius:50%; border:1px dashed rgba(59,130,246,0.4); display:flex; align-items:center; justify-content:center;">
                <div style="width:140px; height:140px; border-radius:50%; border:1px dashed rgba(0,255,163,0.3); display:flex; align-items:center; justify-content:center;">
                    <div style="width:70px; height:70px; border-radius:50%; background:radial-gradient(circle, #3b82f6, #0f172a); display:flex; align-items:center; justify-content:center; font-size:28px;">&#128200;</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-shell" style="text-align:center;">
    <h2 class="h2-nexus">Live Platform <span style="color:var(--p-blue)">Activity</span></h2>
    <p class="p-nexus">Real-time totals across the {{ $brand }} trading community.</p>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px,1fr)); gap:24px;">
        <div class="data-card"><div class="data-val">${{ number_format($totalPayouts) }}</div><div class="data-lab">Payouts Dispatched</div></div>
        <div class="data-card"><div class="data-val">{{ number_format($cUsers) }}</div><div class="data-lab">Active Traders</div></div>
        <div class="data-card"><div class="data-val">${{ number_format($vTx) }}</div><div class="data-lab">Volume Traded</div></div>
    </div>
</section>

<section style="background:#020617; padding: clamp(80px, 12vh, 150px) 24px; color:#fff;">
    <div class="section-shell" style="display:grid; grid-template-columns: 1fr 1fr; gap: clamp(40px,8vw,100px); align-items:center;">
        <div style="display:flex; flex-direction:column; gap:16px;">
            <div style="height:80px; border-radius:16px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; font-weight:800; letter-spacing:1px; font-size:13px;">ENCRYPTED WALLETS</div>
            <div style="height:80px; border-radius:16px; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; font-weight:800; letter-spacing:1px; font-size:13px;">KYC VERIFICATION</div>
            <div style="height:80px; border-radius:16px; background:rgba(59,130,246,0.15); border:1px solid rgba(59,130,246,0.4); display:flex; align-items:center; justify-content:center; font-weight:800; letter-spacing:1px; font-size:13px; color:#3b82f6;">TWO-FACTOR AUTH</div>
        </div>
        <div>
            <div style="display:inline-block; padding: 6px 16px; background:rgba(59,130,246,0.15); color:#3b82f6; border-radius:100px; font-size:11px; font-weight:900; letter-spacing:1px; margin-bottom:20px;">SECURITY</div>
            <h2 class="h2-nexus" style="color:#fff;">Your Funds, Protected<br>Every Step of the Way</h2>
            <p style="color:#94a3b8; font-size:17px; line-height:1.8;">Every deposit, withdrawal and trade runs through encrypted infrastructure, with identity verification standing between your funds and anyone else.</p>

            <div class="spec-list">
                <div class="spec-item"><div class="spec-check">&#10003;</div><div><strong>Encrypted Wallet Balances</strong><p style="color:#94a3b8; font-size:14px;">Your deposits and trading balances are stored securely at every step.</p></div></div>
                <div class="spec-item"><div class="spec-check">&#10003;</div><div><strong>Identity Verification (KYC)</strong><p style="color:#94a3b8; font-size:14px;">Withdrawals are gated behind document verification to protect your account.</p></div></div>
                <div class="spec-item"><div class="spec-check">&#10003;</div><div><strong>Two-Factor Authentication</strong><p style="color:#94a3b8; font-size:14px;">Add an extra layer of login security with optional 2FA.</p></div></div>
            </div>
        </div>
    </div>
</section>
@endsection
