@extends('layouts.marketing')

@section('title', 'Contact Support')

@section('content')
<style>
    :root { --p-blue: #2563eb; --p-dark: #0f172a; --p-border: #e2e8f0; }
    .nexus-shell { padding: clamp(20px, 5vw, 80px) 20px; max-width: 1400px; margin: 0 auto; }
    .nexus-stack { display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start; }
    .pillar-form { flex: 1; min-width: 320px; }
    .pillar-monitor { flex: 1.4; min-width: 320px; }
    .card-terminal { background: #ffffff; border: 1px solid var(--p-border); border-radius: 24px; padding: clamp(25px, 4vw, 40px); box-shadow: 0 15px 40px rgba(0,0,0,0.03); }
    .node-input { width: 100%; padding: 16px; border: 1.5px solid var(--p-border); border-radius: 14px; outline: none; font-size: 16px; margin-bottom: 20px; transition: 0.3s; background: #fcfdfe; }
    .node-input:focus { border-color: var(--p-blue); background: #fff; box-shadow: 0 0 0 4px rgba(37,99,235,0.05); }
    .btn-supreme { width: 100%; padding: 18px; background: var(--p-dark); color: #fff; border: none; border-radius: 14px; font-weight: 900; cursor: pointer; transition: 0.3s; font-size: 15px; }
    .btn-supreme:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    @media (max-width: 768px) { .nexus-shell { padding: 20px 15px; } .pillar-form, .pillar-monitor { flex: 1 0 100%; } }
</style>

<div class="nexus-shell">
    <div style="text-align: center; margin-bottom: 50px;">
        <h1 style="font-size: clamp(30px, 5vw, 50px); font-weight: 950; letter-spacing: -2px;">Contact <span style="color:var(--p-blue)">Support.</span></h1>
        <p style="color:#64748b; font-size: 16px;">Get in touch with the {{ config('app.name') }} support team.</p>
    </div>

    <div class="nexus-stack">
        <div class="pillar-form">
            <div class="card-terminal">
                <h3 style="font-weight: 900; margin-bottom: 10px; font-size: 22px;">Send a Message</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 30px;">Questions about trading, deposits, or your account? We're here to help.</p>

                @if (session('status'))
                    <div style="background:#d1fae5; color:#065f46; padding:20px; border-radius:15px; margin-bottom:30px; border: 1px solid #34d399;">
                        &#9989; {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('marketing.contact.store') }}">
                    @csrf
                    <input type="email" name="email" class="node-input" required placeholder="Your Email" value="{{ old('email') }}">
                    <input type="text" name="subject" class="node-input" required placeholder="Subject" value="{{ old('subject') }}">
                    <textarea name="message" class="node-input" style="height:120px; resize:none;" required placeholder="Describe your issue or question...">{{ old('message') }}</textarea>
                    @if ($errors->any())
                        <div style="color:#e11d48; font-size:13px; font-weight:700; margin-bottom:15px;">{{ $errors->first() }}</div>
                    @endif
                    <button type="submit" class="btn-supreme">Send Message</button>
                </form>
            </div>
        </div>

        <div class="pillar-monitor">
            <div class="card-terminal" style="border-top: 5px solid var(--p-blue);">
                <h3 style="font-weight: 900; margin-bottom: 10px; font-size: 22px;">Your Support Tickets</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 30px;">Track and reply to support responses from your dashboard.</p>

                @auth
                    @if (Route::has('support-tickets.index'))
                        <a href="{{ route('support-tickets.index') }}" class="btn-supreme" style="display:block; text-align:center; text-decoration:none;">View My Support Tickets</a>
                    @else
                        <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:14px; padding:20px; text-align:center; color:#94a3b8; font-size:13px;">
                            Ticket tracking is being deployed &mdash; check back soon.
                        </div>
                    @endif
                @else
                    <div style="background:#f8fafc; border:1px dashed #cbd5e1; border-radius:14px; padding:20px; text-align:center; color:#64748b; font-size:14px;">
                        <a href="{{ route('login') }}" style="color:var(--p-blue); font-weight:800; text-decoration:none;">Sign in</a> to track and reply to your support tickets.
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
