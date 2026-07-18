@extends('layouts.desktop.trading')

@section('title', 'My P2P Trades')

@section('content')
<style>
    .p2p-shell { width: 100%; max-width: 1000px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .status-badge { padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 900; text-transform: uppercase; }
    .status-pending_payment { background: rgba(245,158,11,0.1); color: #fbbf24; }
    .status-paid { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .status-released { background: rgba(16,185,129,0.1); color: #10b981; }
    .status-disputed { background: rgba(239,68,68,0.1); color: #f87171; }
    .status-cancelled { background: rgba(148,163,184,0.1); color: #94a3b8; }
</style>

<div class="p2p-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <h1 style="font-size: 30px; font-weight: 950; margin: 0; color:#fff;">My P2P Trades</h1>
        <a href="{{ route('p2p-offers.index') }}" style="color:#3b82f6; font-weight:700; font-size:14px; text-decoration:none;">&larr; Browse Offers</a>
    </div>

    <div class="cyber-card">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse: separate; border-spacing: 0 10px; color:#fff;">
                <thead>
                    <tr style="text-align:left; font-size:11px; color:#64748b; text-transform:uppercase;">
                        <th style="padding:0 16px;">Trade</th>
                        <th style="padding:0 16px;">Role</th>
                        <th style="padding:0 16px;">Amount</th>
                        <th style="padding:0 16px;">Status</th>
                        <th style="padding:0 16px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trades as $trade)
                        <tr style="background:rgba(255,255,255,0.02);">
                            <td style="padding:14px 16px; border-radius:14px 0 0 14px;">#{{ $trade->id }}</td>
                            <td style="padding:14px 16px; font-size:13px; color:#94a3b8;">{{ $trade->seller_id === auth()->id() ? 'Seller' : 'Buyer' }}</td>
                            <td style="padding:14px 16px;">{{ formatPrice($trade->amount) }}</td>
                            <td style="padding:14px 16px;"><span class="status-badge status-{{ $trade->status }}">{{ str_replace('_', ' ', $trade->status) }}</span></td>
                            <td style="padding:14px 16px; border-radius:0 14px 14px 0; text-align:right;">
                                <a href="{{ route('p2p-trades.show', $trade) }}" style="color:#3b82f6; font-weight:700; font-size:13px; text-decoration:none;">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center; padding:50px; color:#475569; font-weight:800;">No trades yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $trades->links() }}</div>
    </div>
</div>
@endsection
