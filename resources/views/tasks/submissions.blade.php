@extends('layouts.desktop.trading')

@section('title', 'Task Registry')

@section('content')
<style>
    .duty-shell { width: 100%; max-width: 1000px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .status-badge { padding: 6px 12px; border-radius: 10px; font-size: 10px; font-weight: 900; text-transform: uppercase; }
    .status-pending { background: rgba(245,158,11,0.1); color: #fbbf24; }
    .status-approved { background: rgba(16,185,129,0.1); color: #10b981; }
    .status-rejected { background: rgba(239,68,68,0.1); color: #f87171; }
</style>

<div class="duty-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <h1 style="font-size: 30px; font-weight: 950; margin: 0; color:#fff;">Task Registry</h1>
        <a href="{{ route('tasks.index') }}" style="color:#3b82f6; font-weight:700; font-size:14px; text-decoration:none;">&larr; Duty Registry</a>
    </div>

    <div class="cyber-card">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse: separate; border-spacing: 0 10px; color:#fff;">
                <thead>
                    <tr style="text-align:left; font-size:11px; color:#64748b; text-transform:uppercase;">
                        <th style="padding:0 16px;">Task</th>
                        <th style="padding:0 16px;">Date</th>
                        <th style="padding:0 16px;">Reward</th>
                        <th style="padding:0 16px;">Status</th>
                        <th style="padding:0 16px;">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $sub)
                        <tr style="background:rgba(255,255,255,0.02);">
                            <td style="padding:14px 16px; border-radius:14px 0 0 14px;">{{ $sub->task->title ?? 'Deleted task' }}</td>
                            <td style="padding:14px 16px; font-size:13px; color:#94a3b8;">{{ $sub->submitted_date->format('d M, Y') }}</td>
                            <td style="padding:14px 16px; color:#10b981; font-weight:700;">{{ formatPrice($sub->reward_amount) }}</td>
                            <td style="padding:14px 16px;"><span class="status-badge status-{{ $sub->status }}">{{ $sub->status }}</span></td>
                            <td style="padding:14px 16px; border-radius:0 14px 14px 0; font-size:12px; color:#94a3b8;">{{ $sub->admin_notes }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center; padding:50px; color:#475569; font-weight:800;">No submissions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:20px;">{{ $submissions->links() }}</div>
    </div>
</div>
@endsection
