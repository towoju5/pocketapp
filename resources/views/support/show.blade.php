@extends('layouts.desktop.trading')

@section('title', $ticket->subject)

@section('content')
<style>
    .help-shell { width: 100%; max-width: 700px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; margin-bottom:20px; }
    .bubble { max-width: 85%; padding: 15px; border-radius: 18px; margin-bottom: 12px; font-size: 14px; line-height: 1.6; }
    .b-user { background: #3b82f6; color: #fff; margin-left: auto; border-bottom-right-radius: 2px; }
    .b-admin { background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); border-bottom-left-radius: 2px; }
    .btn-go { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 14px; border-radius: 12px; font-weight: 900; cursor: pointer; width:100%; }
</style>

<div class="help-shell">
    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px; margin-bottom:20px;">&#9989; {{ session('success') }}</div>
    @endif

    <div class="cyber-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="color:#fff; font-weight:900; margin:0;">{{ $ticket->subject }}</h2>
            <span style="padding:6px 14px; border-radius:100px; font-size:11px; font-weight:900; text-transform:uppercase; background:rgba(59,130,246,0.1); color:#3b82f6;">{{ $ticket->status }}</span>
        </div>

        <div style="display:flex; flex-direction:column;">
            @foreach ($ticket->replies as $reply)
                <div class="bubble {{ $reply->is_admin_reply ? 'b-admin' : 'b-user' }}">
                    <small style="font-weight:900; text-transform:uppercase; font-size:9px; display:block; margin-bottom:5px; opacity:0.7;">
                        {{ $reply->is_admin_reply ? 'Support Team' : 'You' }}
                    </small>
                    {{ $reply->message }}
                    <span style="font-size:10px; opacity:0.6; display:block; margin-top:5px;">{{ $reply->created_at->format('d M, H:i') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    @if ($ticket->status !== 'closed')
        <div class="cyber-card">
            <form method="POST" action="{{ route('support-tickets.reply', $ticket) }}" style="margin-bottom:14px;">
                @csrf
                <textarea name="message" rows="3" placeholder="Type your follow-up..." class="brand-input-dark" style="margin-bottom:12px;" required></textarea>
                <button type="submit" class="btn-go">Send Reply</button>
            </form>
            <form method="POST" action="{{ route('support-tickets.close', $ticket) }}">
                @csrf
                <button type="submit" style="background:none; border:1px solid rgba(255,255,255,0.15); color:#94a3b8; font-size:12px; padding:10px 16px; border-radius:10px; cursor:pointer; width:100%;">Close Ticket</button>
            </form>
        </div>
    @endif
</div>
@endsection
