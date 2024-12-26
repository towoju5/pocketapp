@extends('layouts.guest')

@section('title', __('Login'))
@section('content')
    <!-- Form starts -->
    <div class="d-flex align-items-center justify-content-center min-vh-100">
        <form method="POST" action="{{ route('login') }}" class="w-100" style="max-width: 400px;">
            @csrf

            <!-- Logo starts -->
            <a href="{{ url('/') }}" class="auth-logo mt-5 mb-3 d-flex justify-content-center">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="Bootstrap Gallery" />
            </a>
            <!-- Logo ends -->

            <!-- Authbox starts -->
            <div class="auth-box">

                <h4 class="mb-4">Welcome back,</h4>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="mb-3">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required
                            autocomplete="current-password">
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

                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('password.request') }}" class="text-decoration-underline">Forgot password?</a>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">Not
                        registered? Signup</a>
                </div>

            </div>
            <!-- Authbox ends -->

        </form> 
    </div>
    <!-- Form ends -->
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('.btn-outline-secondary');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = togglePassword.querySelector('.bi');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    });
</script>
@endpush
