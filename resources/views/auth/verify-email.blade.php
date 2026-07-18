@extends('layouts.auth')

@section('title', 'Verify Email')
@section('visual-title')One more step<br>to sync.@endsection
@section('visual-subtitle')Confirm your email address to unlock full account access.@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 20px;">Verify Your Email</h2>
    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 25px;">
        Thanks for signing up! Before continuing, could you verify your email address by clicking the link we just sent you? If it didn't arrive, we can send another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">&#9989; A new verification link has been sent to your email address.</div>
    @endif

    <div style="display:flex; align-items:center; justify-content:space-between; gap: 16px; margin-top: 20px;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-go" style="width:auto; padding: 14px 24px;">Resend Link</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none; border:none; color:#64748b; font-size:14px; text-decoration:underline; cursor:pointer;">
                Log Out
            </button>
        </form>
    </div>
@endsection
