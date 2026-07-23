@extends('layouts.auth')

@section('title', 'Two-Factor Verification')
@section('visual-title')Confirm Your<br>Identity.@endsection
@section('visual-subtitle')Enter the 6-digit code from your authenticator app to continue.@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 30px;">Two-Factor Verification</h2>

    @if ($errors->any())
        <div class="alert alert-error">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <p style="color:#64748b; font-size:14px; margin-bottom:20px;">
        Open your authenticator app and enter the current 6-digit code for this account.
    </p>

    <form method="POST" action="{{ route('two-factor.verify') }}">
        @csrf

        <label for="one_time_password" class="field-label">Authentication Code</label>
        <input type="text" id="one_time_password" name="one_time_password" class="input-box" placeholder="123456" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" autofocus required>

        <button type="submit" class="btn-go">Verify &amp; Continue</button>
    </form>

    <p style="margin-top: 30px; text-align: center; color: #64748b; font-size: 14px;">
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="background:none; border:none; color: var(--p-blue); font-weight: 800; cursor:pointer; padding:0; font-size:14px;">Sign out instead</button>
        </form>
    </p>
@endsection
