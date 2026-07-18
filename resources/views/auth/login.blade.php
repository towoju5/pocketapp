@extends('layouts.auth')

@section('title', 'Login')
@section('visual-title')Welcome Back.<br>Sign In.@endsection
@section('visual-subtitle')Access the Omni-Layer network. Synchronize your neural signature to harvest daily yields.@endsection
@section('nav-cta')
    <a href="{{ route('register') }}" class="nav-link" style="color:var(--p-blue)">Join Network</a>
@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 30px;">Sign In</h2>

    @if (session('status'))
        <div class="alert alert-success">&#9989; {{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email" class="field-label">Email Address</label>
        <input type="email" id="email" name="email" class="input-box" placeholder="mariavance@gmail.com" value="{{ old('email') }}" autocomplete="username" autofocus required>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <label for="password" class="field-label" style="margin:0;">Access Key</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 11px; color: var(--p-blue); text-decoration: none; font-weight: 700;">Lost Signature?</a>
            @endif
        </div>
        <input type="password" id="password" name="password" class="input-box" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="current-password" required>

        <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:#64748b; font-weight:600; margin-bottom:20px;">
            <input type="checkbox" name="remember" style="width:auto; margin:0;">
            Remember me
        </label>

        <button type="submit" class="btn-go">Initialize Connection</button>
    </form>

    <p style="margin-top: 30px; text-align: center; color: #64748b; font-size: 14px;">
        New here? <a href="{{ route('register') }}" style="color: var(--p-blue); font-weight: 800; text-decoration: none;">Register Here</a>
    </p>
@endsection
