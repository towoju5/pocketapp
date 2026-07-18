@extends('layouts.auth')

@section('title', 'Recover Access')
@section('visual-title')Lost Signature?<br>No problem.@endsection
@section('visual-subtitle')Enter your email address and we'll send you a link to reset your password.@endsection
@section('nav-cta')
    <a href="{{ route('login') }}" class="nav-link" style="color:var(--p-blue)">Sign In</a>
@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 30px;">Reset Access Key</h2>

    @if (session('status'))
        <div class="alert alert-success">&#9989; {{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-error">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email" class="field-label">Email Address</label>
        <input type="email" id="email" name="email" class="input-box" placeholder="mariavance@gmail.com" value="{{ old('email') }}" autofocus required>

        <button type="submit" class="btn-go">Transmit Recovery Link</button>
    </form>
@endsection
