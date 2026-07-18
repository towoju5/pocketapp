@extends('layouts.desktop.trading')

@section('title', 'Duty Registry')

@section('content')
<style>
    .duty-shell { width: 100%; max-width: 1100px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 28px; }
    .task-card { background: rgba(15, 23, 42, 0.85); border: 1px solid rgba(255,255,255,0.08); border-radius: 24px; padding: 24px; }
    .btn-go { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 12px 18px; border-radius: 12px; font-weight: 800; cursor: pointer; text-decoration:none; display:inline-block; font-size:13px; }
    .btn-disabled { background: rgba(255,255,255,0.05); color: #64748b; border: none; padding: 12px 18px; border-radius: 12px; font-weight: 800; cursor: not-allowed; font-size:13px; }
</style>

<div class="duty-shell">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px; margin-bottom:30px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 950; margin: 0; color:#fff;">Duty <span style="color:#3b82f6;">Registry</span></h1>
            <p style="color: #64748b; margin-top: 8px;">Complete daily tasks for bonus wallet credits.</p>
        </div>
        <a href="{{ route('tasks.submissions') }}" style="color:#3b82f6; font-weight:700; font-size:14px; text-decoration:none;">My Submissions &rarr;</a>
    </div>

    @if (session('success'))
        <div style="background:rgba(16,185,129,0.1); border-left:5px solid #10b981; color:#4ade80; padding:20px; border-radius:15px; margin-bottom:20px;">&#9989; {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ session('error') }}</div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
        @forelse ($tasks as $task)
            @php $done = ($todaysCounts[$task->id] ?? 0) >= $task->daily_limit; @endphp
            <div class="task-card">
                <h3 style="color:#fff; font-weight:800; margin:0 0 8px 0;">{{ $task->title }}</h3>
                <p style="color:#94a3b8; font-size:13px; margin-bottom:16px;">{{ $task->description }}</p>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <span style="color:#10b981; font-weight:800; font-size:16px;">{{ formatPrice($task->reward_amount) }}</span>
                    <span style="color:#64748b; font-size:11px; text-transform:uppercase;">{{ $todaysCounts[$task->id] ?? 0 }} / {{ $task->daily_limit }} today</span>
                </div>

                <a href="{{ $task->external_url }}" target="_blank" class="btn-go" style="width:100%; text-align:center; box-sizing:border-box; margin-bottom:12px;">Start Duty</a>

                @if ($done)
                    <button class="btn-disabled" style="width:100%; box-sizing:border-box;" disabled>Limit Reached</button>
                @else
                    <form method="POST" action="{{ route('tasks.submit', $task) }}">
                        @csrf
                        <input type="url" name="proof_url" placeholder="Proof URL" class="brand-input-dark" required style="margin-bottom:10px;">
                        <button type="submit" class="btn-go" style="width:100%; box-sizing:border-box;">Submit Proof</button>
                    </form>
                @endif
            </div>
        @empty
            <div style="grid-column:1/-1; padding:50px; background:rgba(255,255,255,0.02); border:2px dashed rgba(255,255,255,0.1); border-radius:24px; text-align:center;">
                <p style="color:#94a3b8; font-weight:700;">No duties available right now.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
