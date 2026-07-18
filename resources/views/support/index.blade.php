@extends('layouts.desktop.trading')

@section('title', 'Help Desk')

@section('content')
<style>
    .help-shell { width: 100%; max-width: 900px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .btn-go { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 800; cursor: pointer; text-decoration:none; display:inline-block; font-size:13px; }
    .status-badge { padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 900; text-transform: uppercase; }
    .status-open { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .status-pending { background: rgba(245,158,11,0.1); color: #fbbf24; }
    .status-closed { background: rgba(148,163,184,0.1); color: #94a3b8; }
</style>

<div class="help-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <h1 style="font-size: 30px; font-weight: 950; margin: 0; color:#fff;">Help Desk</h1>
        <a href="{{ route('support-tickets.create') }}" class="btn-go">New Ticket</a>
    </div>

    <div class="cyber-card">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse: separate; border-spacing: 0 10px; color:#fff;">
                <thead>
                    <tr style="text-align:left; font-size:11px; color:#64748b; text-transform:uppercase;">
                        <th style="padding:0 16px;">Subject</th><th style="padding:0 16px;">Priority</th><th style="padding:0 16px;">Status</th><th style="padding:0 16px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr style="background:rgba(255,255,255,0.02);">
                            <td style="padding:14px 16px; border-radius:14px 0 0 14px;">{{ $ticket->subject }}</td>
                            <td style="padding:14px 16px; text-transform:capitalize; font-size:13px; color:#94a3b8;">{{ $ticket->priority }}</td>
                            <td style="padding:14px 16px;"><span class="status-badge status-{{ $ticket->status }}">{{ $ticket->status }}</span></td>
                            <td style="padding:14px 16px; border-radius:0 14px 14px 0; text-align:right;">
                                <a href="{{ route('support-tickets.show', $ticket) }}" style="color:#3b82f6; font-weight:700; font-size:13px; text-decoration:none;">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; padding:50px; color:#475569; font-weight:800;">No tickets yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $tickets->links() }}</div>
    </div>
</div>
@endsection
