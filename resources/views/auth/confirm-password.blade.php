@extends('layouts.auth')

@section('title', 'Confirm Identity')
@section('visual-title')Just confirming<br>it's you.@endsection
@section('visual-subtitle')This is a secure area of the network — re-enter your access key to continue.@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 20px;">Confirm Access Key</h2>
    <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 25px;">
        This is a secure area of the network. Please confirm your access key before continuing.
    </p>

    @if ($errors->any())
        <div class="alert alert-error">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <label for="password" class="field-label">Access Key</label>
        <input type="password" id="password" name="password" class="input-box" autocomplete="current-password" autofocus required>

        <button type="submit" class="btn-go">Confirm</button>
    </form>
@endsection
