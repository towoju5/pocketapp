@extends('layouts.marketing')

@php $brand = config('app.name'); @endphp

@section('title', 'Trading Blog')

@section('content')
<style>
    :root { --p-blue: #2563eb; --p-dark: #0f172a; --bg-soft: #f8fafc; }
    .blog-shell { padding: clamp(40px, 8vw, 100px) 24px; max-width: 1000px; margin: 0 auto; }
    .news-node { background: #fff; border: 1px solid rgba(0,0,0,0.06); border-radius: 24px; margin-bottom: 20px; overflow: hidden; transition: 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); }
    .news-node:hover { border-color: var(--p-blue); transform: translateY(-3px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
    .news-trigger { width: 100%; padding: 30px; background: none; border: none; display: flex; flex-direction: column; text-align: left; cursor: pointer; }
    .news-meta { display: flex; align-items: center; gap: 15px; margin-bottom: 12px; }
    .news-tag { font-size: 10px; font-weight: 900; color: var(--p-blue); background: rgba(37,99,235,0.08); padding: 4px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 1px; }
    .news-date { font-size: 12px; color: #94a3b8; font-weight: 600; }
    .news-title { font-size: clamp(18px, 3vw, 22px); font-weight: 900; color: var(--p-dark); margin: 0; line-height: 1.3; }
    .news-content { padding: 0 30px 40px 30px; color: #475569; font-size: 16px; line-height: 1.8; }
    .news-node.is-active { border-color: var(--p-blue); }
    .news-node.is-active .news-title { color: var(--p-blue); }
    .gen-badge { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: var(--p-dark); color: #fff; border-radius: 100px; font-size: 11px; font-weight: 800; margin-bottom: 30px; }
    .pulse-news { width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: p-glow 1.5s infinite; }
    @keyframes p-glow { 0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.7); } 100% { box-shadow: 0 0 0 10px rgba(16,185,129,0); } }
    @media (max-width: 600px) { .blog-shell { padding: 40px 16px; } .news-trigger { padding: 20px; } .news-content { padding: 0 20px 30px 20px; } }
</style>

<div class="blog-shell" x-data="{ open: null }">
    <div style="text-align: center; margin-bottom: 60px;">
        <div class="gen-badge"><span class="pulse-news"></span> LIVE PLATFORM UPDATES</div>
        <h1 style="font-size: clamp(34px, 5vw, 60px); font-weight: 950; color: var(--p-dark); letter-spacing: -3px; line-height: 1; margin-bottom: 20px;">Trading Blog.</h1>
        <p style="font-size: 18px; color: #64748b; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Daily updates on markets, platform improvements, and news from the <b>{{ $brand }}</b> team.
        </p>
    </div>

    @foreach ($news as $item)
        <div class="news-node" x-bind:class="open === {{ $item['id'] }} ? 'is-active' : ''">
            <button type="button" class="news-trigger" @click="open = (open === {{ $item['id'] }} ? null : {{ $item['id'] }})">
                <div class="news-meta">
                    <span class="news-tag">{{ $item['category'] }}</span>
                    <span class="news-date">{{ $item['timestamp'] }}</span>
                </div>
                <h3 class="news-title">{{ $item['title'] }}</h3>
            </button>
            <div x-show="open === {{ $item['id'] }}" x-collapse x-cloak class="news-content">
                <div style="height:1px; background:rgba(0,0,0,0.05); margin-bottom:25px;"></div>
                {{ $item['body'] }}
                <div style="margin-top: 30px;">
                    <a href="{{ route('register') }}" style="font-size: 12px; font-weight: 900; color: var(--p-blue); text-decoration: none; text-transform: uppercase;">&#9679; Create an account to learn more</a>
                </div>
            </div>
        </div>
    @endforeach

    <div style="text-align: center; margin-top: 60px; padding: 30px; border: 1px dashed #e2e8f0; border-radius: 20px;">
        <p style="color: #94a3b8; font-size: 13px; font-weight: 600;" x-data="{ countdown: '--:--:--' }"
           x-init="setInterval(() => {
               const now = new Date();
               const tomorrow = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1);
               const diff = tomorrow - now;
               const h = Math.floor(diff / 3600000);
               const m = Math.floor((diff % 3600000) / 60000);
               const s = Math.floor((diff % 60000) / 1000);
               countdown = [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
           }, 1000)">
            Next update in: <span style="color:var(--p-dark);" x-text="countdown"></span>
        </p>
    </div>
</div>
@endsection
