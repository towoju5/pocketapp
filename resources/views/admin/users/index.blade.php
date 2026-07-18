@extends('layouts.admin.app')

@section('title', 'User Management')

@section('content')
<style>
    * { box-sizing: border-box; -webkit-font-smoothing: antialiased; }
    .directory-shell { width: 100%; max-width: 1400px; margin: 0 auto; display: flex; flex-direction: column; gap: 30px; }
    .glass-card { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 35px; padding: 35px; width: 100%; transition: 0.4s; }
    .user-table-wrapper { overflow-x: auto; width: 100%; border-radius: 20px; }
    .node-row { background: rgba(255,255,255,0.01); border-bottom: 1px solid rgba(255,255,255,0.03); transition: 0.3s; }
    .node-row:hover { background: rgba(255,255,255,0.04); }
    .quick-input { background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); padding: 10px 12px; border-radius: 10px; color: #fff; font-size: 13px; outline: none; width: 100%; }
    .quick-input:focus { border-color: #3b82f6; }
    .btn-node { padding: 10px 15px; border-radius: 10px; border: none; font-weight: 800; font-size: 11px; text-transform: uppercase; cursor: pointer; transition: 0.2s; }
    .btn-blue { background: #3b82f6; color: #fff; }
    .btn-ghost { background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1); }
    .status-pill { font-size: 9px; font-weight: 900; text-transform: uppercase; padding: 4px 8px; border-radius: 6px; letter-spacing: 1px; }
    .status-verified { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    @media (max-width: 1024px) {
        .glass-card { padding: 25px 15px; border-radius: 25px; }
        .action-flex { flex-direction: column; align-items: stretch !important; gap: 10px; }
    }
</style>

<div class="directory-shell">

    <div class="glass-card" style="text-align: center; background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.1), transparent);">
        <div style="display:inline-block; padding:8px 16px; background:rgba(59, 130, 246, 0.1); border-radius:100px; color:#60a5fa; font-size:10px; font-weight:900; letter-spacing:1px; margin-bottom:20px; border:1px solid rgba(59, 130, 246, 0.2);">
            GLOBAL IDENTITY REGISTRY
        </div>
        <h1 style="margin:0; font-weight:950; color:#fff; letter-spacing:-2.5px; font-size: clamp(30px, 6vw, 55px);">User <span style="color:#3b82f6;">Management</span></h1>
        <p style="color:#64748b; font-size:16px; margin-top:12px;">Admin-level control over every registered trading account.</p>
    </div>

    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); color:#4ade80; padding:20px; border-radius:20px; font-weight:800; border-left:6px solid #10b981;">&#128752; {{ session('success') }}</div>
    @endif

    <div class="glass-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; flex-wrap:wrap; gap:15px;">
            <h3 style="margin:0; font-weight:900; color:#fff; font-size: 20px;">Master Operator Roster</h3>
            <div style="display:flex; gap:10px;">
                <span class="status-pill status-verified">&#9679; Network Secure</span>
                <span class="status-pill" style="background:rgba(255,255,255,0.05); color:#fff;">Nodes: {{ $users->total() }}</span>
            </div>
        </div>

        <div class="user-table-wrapper">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align:left; color:#475569; font-size:11px; text-transform:uppercase; letter-spacing:1.5px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th style="padding:15px;">Operator Identity</th>
                        <th style="padding:15px;">Liquidity</th>
                        <th style="padding:15px;">Protocol Actions</th>
                        <th style="padding:15px;">Quick Override</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="node-row">
                            <td style="padding:20px;">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div style="width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg, #3b82f6, #10b981); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:900;">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:800; color:#fff; font-size:14px;">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                            @if (($user->kyc->status ?? null) === 'verified')
                                                <span style="color:#10b981; margin-left:5px;">&#10003;</span>
                                            @endif
                                        </div>
                                        <div style="font-size:11px; color:#475569; font-family:monospace;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:20px;">
                                <div style="font-weight:900; color:#fff; font-size:16px;">{{ formatPrice($user->wallets->sum('balance')) }}</div>
                                <div style="font-size:10px; color:#10b981; font-weight:800; text-transform:uppercase;">Wallet Balance</div>
                            </td>
                            <td style="padding:20px;">
                                <div style="display:flex; gap:8px;">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn-node btn-blue">Inspect</a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-node btn-ghost">Edit</a>
                                </div>
                            </td>
                            <td style="padding:20px;">
                                <form method="POST" action="{{ route('admin.wallets.credit', $user->id) }}" class="action-flex" style="display:flex; gap:8px; align-items:center;">
                                    @csrf
                                    <input type="hidden" name="wallet" value="qt_real_usd">
                                    <input type="number" step="0.01" name="amount" class="quick-input" placeholder="0.00" style="width:100px;" required>
                                    <button type="submit" class="btn-node" style="background:#f59e0b; color:#000;">Add Funds</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding:40px; color:#475569; font-weight:800;">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">{{ $users->links() }}</div>
    </div>

</div>
@endsection
