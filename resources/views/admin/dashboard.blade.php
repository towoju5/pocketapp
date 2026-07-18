@extends('layouts.admin.app')

@section('title', 'Command Matrix')

@php
    $activeUsers = \App\Models\User::count();
    $totalLiquidity = \App\Models\User::with('wallets')->get()->flatMap->wallets->sum('balance');
    $pendingPh = \App\Models\Deposit::where('deposit_status', 'pending')->sum('deposit_amount');
    $pendingGh = \App\Models\Payout::where('payout_status', 'pending')->sum('payout_amount');
    $recentRequests = \App\Models\Deposit::with('user')->where('deposit_status', 'pending')
        ->latest()->limit(6)->get();
@endphp

@section('content')
<style>
    * { box-sizing: border-box; -webkit-font-smoothing: antialiased; }

    .admin-shell { width: 100%; max-width: 1500px; margin: 0 auto; display: flex; flex-direction: column; gap: 30px; }

    .command-card { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 35px; padding: 40px; transition: 0.4s cubic-bezier(0.16, 1, 0.3, 1); width: 100%; }
    .command-card:hover { transform: translateY(-8px); border-color: #f59e0b; box-shadow: 0 40px 80px -20px rgba(0,0,0,0.7); }

    .module-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
    .module-link { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 25px; text-decoration: none; display: flex; align-items: center; gap: 20px; transition: 0.3s; }
    .module-link:hover { background: rgba(245, 158, 11, 0.1); border-color: #f59e0b; transform: scale(1.02); }
    .module-icon { width: 50px; height: 50px; background: rgba(0,0,0,0.3); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 22px; }

    .ledger-row { background: rgba(255,255,255,0.01); border-radius: 15px; transition: 0.2s; }
    .ledger-row:hover { background: rgba(255,255,255,0.04); }

    .bento-grid-master { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr)); gap: 20px; width: 100%; }
    .command-card-v101 { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 24px; padding: 25px; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; overflow: hidden; }
    .command-card-v101:hover { transform: translateY(-8px); border-color: rgba(255,255,255,0.15); box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
    .stat-label-nano { font-size: 10px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; display: block; }
    .stat-val-mega { font-size: clamp(22px, 3vw, 28px); font-weight: 950; color: #fff; letter-spacing: -1px; }
    .glow-line { position: absolute; top: 0; left: 0; width: 100%; height: 4px; border-radius: 4px 4px 0 0; }

    @media (max-width: 768px) {
        .command-card { padding: 25px; border-radius: 30px; }
        .stat-val { font-size: 22px; }
    }
</style>

<div class="admin-shell">

    <div class="command-card" style="background: radial-gradient(circle at center, rgba(245, 158, 11, 0.1), transparent); text-align: center;">
        <div style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px; background:rgba(245, 158, 11, 0.1); border-radius:100px; color:#f59e0b; font-size:10px; font-weight:900; letter-spacing:1px; margin-bottom:20px; border:1px solid rgba(245, 158, 11, 0.2);">
            <span style="height:6px; width:6px; background:#f59e0b; border-radius:50%; box-shadow: 0 0 10px #f59e0b;"></span>
            GOD-ADMIN LEVEL AUTHORIZED
        </div>
        <h1 style="margin:0; font-weight:950; color:#fff; letter-spacing:-3px; font-size: clamp(30px, 6vw, 56px);">Command <span style="color:#f59e0b;">Matrix</span></h1>
        <p style="color:#94a3b8; font-size:17px; margin-top:12px;">Global System Parameters &amp; Infrastructure Control</p>
    </div>

    <div class="bento-grid-master">
        <div class="command-card-v101">
            <div class="glow-line" style="background: #3b82f6; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);"></div>
            <span class="stat-label-nano">Global Liquidity</span>
            <div class="stat-val-mega" style="color: #60a5fa;">{{ formatPrice($totalLiquidity) }}</div>
            <div style="font-size: 9px; color: #334155; margin-top: 5px; font-weight: 800;">TOTAL_AVAILABLE_RESERVE</div>
        </div>

        <div class="command-card-v101">
            <div class="glow-line" style="background: #10b981; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);"></div>
            <span class="stat-label-nano">Active Users</span>
            <div class="stat-val-mega" style="color: #34d399;">{{ number_format($activeUsers) }}</div>
            <div style="font-size: 9px; color: #334155; margin-top: 5px; font-weight: 800;">SYNCHRONIZED_OPERATORS</div>
        </div>

        <div class="command-card-v101">
            <div class="glow-line" style="background: #f59e0b; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);"></div>
            <span class="stat-label-nano">Pending PH</span>
            <div class="stat-val-mega" style="color: #fbbf24;">{{ formatPrice($pendingPh) }}</div>
            <div style="font-size: 9px; color: #334155; margin-top: 5px; font-weight: 800;">INBOUND_CAPITAL_FLOW</div>
        </div>

        <div class="command-card-v101">
            <div class="glow-line" style="background: #ef4444; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);"></div>
            <span class="stat-label-nano">Pending GH</span>
            <div class="stat-val-mega" style="color: #f87171;">{{ formatPrice($pendingGh) }}</div>
            <div style="font-size: 9px; color: #334155; margin-top: 5px; font-weight: 800;">OUTBOUND_SETTLEMENT_QUEUE</div>
        </div>
    </div>

    <div class="command-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
            <h3 style="margin:0; font-weight:900; color:#fff; font-size: 22px;">Real-Time Escrow Queue</h3>
            @if (Route::has('admin.p2p-trades.index'))
                <a href="{{ route('admin.p2p-trades.index') }}" style="color:#f59e0b; text-decoration:none; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:1px;">Launch Monitor &rarr;</a>
            @endif
        </div>

        <div style="overflow-x: auto;">
            <table style="width:100%; border-collapse: separate; border-spacing: 0 10px;">
                <thead>
                    <tr style="color: #475569; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">
                        <th style="padding: 10px; text-align: left;">Operator</th>
                        <th style="padding: 10px; text-align: left;">Action</th>
                        <th style="padding: 10px; text-align: right;">Quantum</th>
                        <th style="padding: 10px; text-align: right;">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentRequests as $req)
                        <tr class="ledger-row">
                            <td style="padding:15px; border-radius: 15px 0 0 15px; color:#fff; font-weight:700;">{{ $req->user->first_name ?? 'Trader' }} {{ $req->user->last_name ?? '' }}</td>
                            <td style="padding:15px;">
                                <span style="font-size:10px; font-weight:900; padding:5px 10px; border-radius:6px; background:rgba(16,185,129,0.1); color:#10b981;">DEPOSIT</span>
                            </td>
                            <td style="padding:15px; text-align:right; font-weight:900; color:#fff;">{{ formatPrice($req->deposit_amount) }}</td>
                            <td style="padding:15px; border-radius: 0 15px 15px 0; text-align:right; color:#64748b; font-size:12px;">{{ $req->created_at->format('H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding:40px; color:#475569; font-weight:800;">[ ALL LEDGER REQUESTS SYNCHRONIZED ]</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <h3 style="margin: 10px 0 0 0; font-weight: 950; color: #64748b; font-size: 14px; text-transform: uppercase; letter-spacing: 3px; text-align: center;">Infrastructure Subsystems</h3>
    <div class="module-grid">
        <a href="{{ route('admin.users.index') }}" class="module-link">
            <div class="module-icon">&#128101;</div>
            <div><div style="font-weight:900; color:#fff;">User Registry</div><small style="color:#64748b;">Manage User Identities</small></div>
        </a>
        <a href="{{ route('admin.wallets.index') }}" class="module-link">
            <div class="module-icon">&#127974;</div>
            <div><div style="font-weight:900; color:#fff;">Central Bank</div><small style="color:#64748b;">Balance Adjustments</small></div>
        </a>
        @if (Route::has('admin.p2p-trades.index'))
            <a href="{{ route('admin.p2p-trades.index') }}" class="module-link">
                <div class="module-icon">&#9878;&#65039;</div>
                <div><div style="font-weight:900; color:#fff;">Escrow Engine</div><small style="color:#64748b;">Match Management</small></div>
            </a>
        @endif
        @if (Route::has('admin.tasks.index'))
            <a href="{{ route('admin.tasks.index') }}" class="module-link">
                <div class="module-icon">&#127919;</div>
                <div><div style="font-weight:900; color:#fff;">Yield Logic</div><small style="color:#64748b;">Network Task Config</small></div>
            </a>
        @endif
        @if (Route::has('admin.kyc.index'))
            <a href="{{ route('admin.kyc.index') }}" class="module-link">
                <div class="module-icon">&#127760;</div>
                <div><div style="font-weight:900; color:#fff;">Identity Vault</div><small style="color:#64748b;">KYC Review Queue</small></div>
            </a>
        @endif
        @if (Route::has('admin.support-tickets.index'))
            <a href="{{ route('admin.support-tickets.index') }}" class="module-link">
                <div class="module-icon">&#128065;</div>
                <div><div style="font-weight:900; color:#fff;">Support Desk</div><small style="color:#64748b;">Ticket Monitoring</small></div>
            </a>
        @endif
    </div>

</div>
@endsection
