@extends('layouts.auth')

@section('title', 'Reset Access Key')
@section('visual-title')Choose a new<br>access key.@endsection
@section('visual-subtitle')Make it strong, unique, and easy for you to remember.@endsection

@section('content')
    <h2 style="font-size: 32px; font-weight: 950; color: var(--p-dark); letter-spacing: -1.5px; margin-bottom: 30px;">Set New Access Key</h2>

    @if ($errors->any())
        <div class="alert alert-error">&#9888;&#65039; {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <label for="email" class="field-label">Email Address</label>
        <input type="email" id="email" name="email" class="input-box" value="{{ old('email', $request->email) }}" autofocus autocomplete="username" required>

        <label for="password" class="field-label">New Access Key</label>
        <input type="password" id="password" name="password" class="input-box" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="new-password" required>

        <label for="password_confirmation" class="field-label">Confirm Access Key</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="input-box" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="new-password" required>

        <button type="submit" class="btn-go">Reset Access Key</button>
    </form>
@endsection
