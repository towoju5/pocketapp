@extends('layouts.auth')

@section('title', 'Create Account')
@section('visual-title')The Premier<br>Algorithmic P2P Matrix.@endsection
@section('visual-subtitle')Create your account to start trading. Access live markets, signals, and secure deposits/withdrawals.@endsection
@section('nav-cta')
    <a href="{{ route('login') }}" class="nav-link" style="color:var(--p-blue)">Sign In</a>
@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 30px;">Create Account</h2>

    @if ($errors->any())
        <div class="alert alert-error">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="field-label">Entity Designation (Full Name)</label>
            <input type="text" id="name" name="name" class="input-box" placeholder="Maria Vance" value="{{ old('name') }}" autocomplete="name" autofocus required>
        </div>

        <div class="form-group">
            <label for="email" class="field-label">Email Address</label>
            <input type="email" id="email" name="email" class="input-box" placeholder="name@email.com" value="{{ old('email') }}" autocomplete="username" required>
        </div>

        <div class="form-group">
            <label for="password" class="field-label">Neurological Password</label>
            <input type="password" id="password" name="password" class="input-box" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="new-password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="field-label">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="input-box" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="new-password" required>
        </div>

        <div class="form-group">
            <label for="referrer_info" class="field-label">Referrer Email or ID (Optional)</label>
            <input type="text" id="referrer_info" name="referrer_info" class="input-box" placeholder="Optional" value="{{ old('referrer_info', request('ref')) }}">
        </div>

        <button type="submit" class="btn-go">Create Account</button>
    </form>

    <p style="margin-top: 30px; text-align: center; color: #64748b; font-size: 14px;">
        Already have a signature? <a href="{{ route('login') }}" style="color: var(--p-blue); font-weight: 800; text-decoration: none;">Authenticate</a>
    </p>
@endsection
