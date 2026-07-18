@extends('layouts.marketing')

@php $brand = config('app.name'); @endphp

@section('title', 'Knowledge Base & FAQ')

@php
    $faqs = [
        ['q' => 'What is binary options trading?', 'a' => 'You predict whether an asset\'s price will be above or below its current level when a chosen expiry time arrives. A correct prediction pays out the percentage shown before you traded; an incorrect one loses the stake.'],
        ['q' => 'How is my payout calculated?', 'a' => 'Each asset shows its own payout percentage on the trade panel. A winning trade returns your stake plus that percentage in profit, credited to your wallet the moment the trade settles.'],
        ['q' => 'Is my money safe on the platform?', 'a' => 'Yes. Wallet balances are encrypted, withdrawals require identity verification, and optional two-factor authentication adds an extra layer of login security.'],
        ['q' => 'What are the KYC (Know Your Customer) requirements?', 'a' => 'To keep withdrawals secure, we require a government-issued ID and proof of address before releasing funds. Until verified, some withdrawal features remain limited.'],
        ['q' => 'What happens if suspicious account activity is detected?', 'a' => 'Our security checks flag unusual behavior (e.g. failed logins, mismatched device fingerprints). Affected accounts may be temporarily restricted pending manual review.'],
        ['q' => 'How do I create an account?', 'a' => 'Visit the registration page, enter your details, and verify your email. You can start trading on the free demo wallet immediately.'],
        ['q' => 'Can I have more than one account?', 'a' => 'No. We require a strict one-account-per-person policy to keep trading and referral rewards fair for everyone.'],
        ['q' => 'How do I withdraw my balance?', 'a' => 'Once your identity is verified, open the Wallet page, choose Withdraw, select a method, and submit a request. Your funds are sent to the account or address you provide.'],
        ['q' => 'Are my funds insured?', 'a' => 'Wallet balances are held securely, but trading always carries risk of loss. Please review our Risk Disclosure before trading with real funds.'],
        ['q' => 'How often do signals update?', 'a' => 'Trading signals refresh continuously based on live market analysis and are shown with an expiry countdown on the Signals page.'],
        ['q' => "What does a 'Pending' trade status mean?", 'a' => 'It means your trade has been placed and is waiting for its expiry time to be reached, at which point it automatically settles as a win or loss.'],
        ['q' => 'Can I trade on weekends?', 'a' => 'Yes — OTC (over-the-counter) assets remain tradable on weekends and outside standard market hours, priced continuously by liquidity providers.'],
        ['q' => 'How do Tasks work?', 'a' => 'Completing certain in-app tasks (like verifying your profile or engaging with educational content) can unlock bonus credits added directly to your wallet.'],
        ['q' => "Why is my account status 'Suspended'?", 'a' => 'Suspensions can occur after repeated failed login attempts, suspicious device activity, or a compliance review. Contact Support to resolve it.'],
        ['q' => 'What are Investment Plans?', 'a' => 'Investment Plans let you commit funds to a fixed-term plan for a defined return, separate from active binary options trading.'],
    ];
@endphp

@section('content')
<div class="hero-section" style="padding: 80px 24px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; text-align: center;">
    <h1 style="font-size: 48px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.02em; color: #fff;">Knowledge Base &amp; FAQ</h1>
    <p style="font-size: 20px; color: #94a3b8; max-width: 800px; margin: 0 auto; line-height: 1.6;">
        Everything you need to know about trading, wallets, and account security on {{ $brand }}.
    </p>
</div>

<div style="max-width: 800px; margin: 0 auto; padding: 80px 24px;" x-data="{ open: null }">
    @foreach ($faqs as $i => $item)
        <div style="margin-bottom: 24px; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; transition: box-shadow 0.2s;">
            <button type="button" @click="open = (open === {{ $i }} ? null : {{ $i }})"
                    style="font-size: 18px; font-weight: 700; color: #0f172a; cursor: pointer; display: flex; justify-content: space-between; width: 100%; background: none; border: none; text-align: left;"
                    x-bind:style="open === {{ $i }} ? 'color: #2563eb' : ''">
                {{ $item['q'] }}
                <span x-text="open === {{ $i }} ? '&minus;' : '+'"></span>
            </button>
            <div x-show="open === {{ $i }}" x-collapse x-cloak style="font-size: 15px; color: #475569; line-height: 1.6; margin-top: 12px;">
                {{ $item['a'] }}
            </div>
        </div>
    @endforeach
</div>
@endsection
