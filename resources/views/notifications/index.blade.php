@extends('layouts.desktop.trading')

@section('title', 'Notifications')

@section('content')
<style>
    .ntf-shell { width: 100%; max-width: 800px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .ntf-item { display: flex; justify-content: space-between; gap: 16px; padding: 18px; border-radius: 16px; margin-bottom: 10px; background: rgba(255,255,255,0.02); }
    .ntf-item.unread { border: 1px solid rgba(59,130,246,0.3); background: rgba(59,130,246,0.05); }
</style>

<div class="ntf-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:24px;">
        <h1 style="font-size: 28px; font-weight: 950; margin: 0; color:#fff;">Notifications</h1>
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit" style="background:none; border:1px solid rgba(255,255,255,0.15); color:#94a3b8; font-size:12px; padding:8px 14px; border-radius:10px; cursor:pointer;">Mark all read</button>
        </form>
    </div>

    <div class="cyber-card">
        @forelse ($notifications as $n)
            <div class="ntf-item {{ $n->read_at ? '' : 'unread' }}">
                <div>
                    <p style="color:#fff; font-weight:700; margin:0 0 4px 0;">{{ $n->data['title'] ?? 'Notification' }}</p>
                    <p style="color:#94a3b8; font-size:13px; margin:0;">{{ $n->data['body'] ?? '' }}</p>
                    <p style="color:#475569; font-size:11px; margin-top:6px;">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @if (!$n->read_at)
                    <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:#3b82f6; font-size:11px; cursor:pointer;">Mark read</button>
                    </form>
                @endif
            </div>
        @empty
            <p style="text-align:center; color:#475569; padding:40px; font-weight:700;">No notifications yet.</p>
        @endforelse

        <div style="margin-top:16px;">{{ $notifications->links() }}</div>
    </div>
</div>
@endsection
