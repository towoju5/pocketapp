@extends('layouts.desktop.trading')

@section('title', 'New Ticket')

@section('content')
<style>
    .help-shell { width: 100%; max-width: 600px; margin: 0 auto; padding: 40px 24px; }
    .cyber-card { background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(25px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 32px; }
    .btn-go { background: linear-gradient(135deg, #2563eb, #3b82f6); color: #fff; border: none; padding: 16px; border-radius: 14px; font-weight: 900; cursor: pointer; width: 100%; }
</style>

<div class="help-shell">
    @if ($errors->any())
        <div style="background:rgba(239,68,68,0.1); border-left:5px solid #ef4444; color:#f87171; padding:20px; border-radius:15px; margin-bottom:20px;">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <div class="cyber-card">
        <h2 style="color:#fff; font-weight:900; margin:0 0 25px 0;">Open a Ticket</h2>

        <form method="POST" action="{{ route('support-tickets.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="brand-input-dark" required>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Priority</label>
                <select name="priority" class="brand-input-dark" required>
                    <option value="normal">Standard</option>
                    <option value="high">Critical</option>
                    <option value="urgent">Security</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Message</label>
                <textarea name="message" rows="5" class="brand-input-dark" required>{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="btn-go">Submit Ticket</button>
        </form>
    </div>
</div>
@endsection
