@extends('layouts.guest')

@section('title', __('Register'))
@section('content')
    <!-- Form starts -->
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <form method="POST" action="{{ route('register') }}" class="w-100" style="max-width: 400px;">
            @csrf

            <!-- Logo starts -->
            <a href="{{ url('/') }}" class="auth-logo mt-5 mb-3 d-flex justify-content-center">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Bootstrap Gallery" />
            </a>
            <!-- Logo ends -->

            <!-- Authbox starts -->
            <div class="auth-box">

                <h4 class="mb-4">Create an Account</h4>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="mb-3">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name"
                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                            value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Enter password"
                            required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password-confirm">Confirm Password <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" id="password-confirm" name="password_confirmation" class="form-control"
                            placeholder="Confirm password" required autocomplete="new-password">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Already registered? Login</a>
                </div>

            </div>
            <!-- Authbox ends -->

        </form>
    </div>
    <!-- Form ends -->
@endsection
