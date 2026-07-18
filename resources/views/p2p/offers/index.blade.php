@extends('layouts.desktop.trading')

@section('title', 'P2P Escrow')

@section('content')
<style>
    .p2p-shell { width: 100%; max-width: 1100px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .btn-go { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 800; cursor: pointer; text-decoration: none; display:inline-block; font-size:13px; }
    .type-buy { color: #10b981; } .type-sell { color: #f87171; }
</style>

<div class="p2p-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 950; margin: 0; color:#fff;">P2P <span style="color:#3b82f6;">Escrow</span></h1>
            <p style="color: #64748b; margin-top: 8px;">Trade directly with other nodes under escrow protection.</p>
        </div>
        <div style="display:flex; gap:12px;">
            <a href="{{ route('p2p-trades.index') }}" class="btn-go" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);">My Trades</a>
            <a href="{{ route('p2p-offers.create') }}" class="btn-go">New Offer</a>
        </div>
    </div>

    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px; margin-bottom:20px;">&#9989; {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif

    @if ($myOffers->isNotEmpty())
        <div class="cyber-card" style="margin-bottom:24px;">
            <h3 style="margin:0 0 16px 0; color:#fff; font-weight:800;">My Offers</h3>
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach ($myOffers as $offer)
                    <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.02); padding:14px 18px; border-radius:14px;">
                        <div>
                            <span class="type-{{ $offer->type }}" style="font-weight:800; text-transform:uppercase; font-size:12px;">{{ $offer->type }}</span>
                            <span style="color:#fff; margin-left:10px;">{{ $offer->currency }} &bull; {{ formatPrice($offer->price_per_unit) }}/unit &bull; {{ formatPrice($offer->available_amount) }} available</span>
                        </div>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <span style="font-size:11px; color:#94a3b8; text-transform:uppercase;">{{ $offer->status }}</span>
                            @if ($offer->status === 'active')
                                <form method="POST" action="{{ route('p2p-offers.cancel', $offer) }}">
                                    @csrf
                                    <button type="submit" style="background:none; border:1px solid rgba(239,68,68,0.4); color:#f87171; font-size:11px; padding:6px 12px; border-radius:8px; cursor:pointer;">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        @forelse ($offers as $offer)
            <div class="cyber-card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                    <span class="type-{{ $offer->type }}" style="font-weight:900; text-transform:uppercase; font-size:13px;">{{ $offer->type }} {{ $offer->currency }}</span>
                    <span style="color:#64748b; font-size:12px;">{{ $offer->maker->first_name ?? 'Trader' }}</span>
                </div>
                <div style="font-size:24px; font-weight:900; color:#fff; margin-bottom:14px;">{{ formatPrice($offer->price_per_unit) }} <span style="font-size:12px; color:#64748b; font-weight:400;">/ unit</span></div>
                <div style="font-size:13px; color:#94a3b8; margin-bottom:20px;">
                    Limits: {{ formatPrice($offer->min_limit) }} &ndash; {{ formatPrice($offer->max_limit) }}<br>
                    Available: {{ formatPrice($offer->available_amount) }}
                </div>
                <a href="{{ route('p2p-trades.index') }}?offer={{ $offer->id }}" onclick="document.getElementById('trade-form-{{ $offer->id }}').classList.toggle('hidden'); return false;" class="btn-go" style="width:100%; text-align:center; box-sizing:border-box;">Trade</a>

                <form id="trade-form-{{ $offer->id }}" method="POST" action="{{ route('p2p-trades.store', $offer) }}" class="hidden" style="margin-top:14px;">
                    @csrf
                    <input type="number" step="0.01" name="amount" placeholder="Amount" class="brand-input-dark" required style="margin-bottom:10px;">
                    <button type="submit" class="btn-go" style="width:100%; box-sizing:border-box;">Confirm Trade</button>
                </form>
            </div>
        @empty
            <div style="grid-column:1/-1; padding:50px; background:rgba(255,255,255,0.02); border:2px dashed rgba(255,255,255,0.1); border-radius:24px; text-align:center;">
                <p style="color:#94a3b8; font-weight:700;">No open offers right now.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top:20px;">{{ $offers->links() }}</div>
</div>
@endsection
