@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<!-- Team Invitation Message -->
@if (session('message'))
<div class="alert alert-info mb-4" role="alert">
    <div class="d-flex">
        <div class="me-3">
            <i class="bi bi-info-circle-fill fs-4"></i>
        </div>
        <div>
            <h4 class="alert-heading fs-5">Team Invitation</h4>
            <p class="mb-0">{{ session('message') }}</p>
        </div>
    </div>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger mb-4">
    {{ session('error') }}
</div>
@endif

<div class="text-center mb-4">
    <h1 class="fs-2 fw-bold mb-2">Welcome Back!</h1>
    <p class="text-muted">Please sign in to continue</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label fw-bold">
            {{ __('Email Address') }} <span class="text-danger">*</span>
        </label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? request('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-bold">
            {{ __('Password') }} <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            <button type="button" class="btn btn-outline-secondary border border-secondary" onclick="togglePasswordVisibility()">
                <i class="bi bi-eye" id="eye-icon"></i>
                <i class="bi bi-eye-slash d-none" id="eye-off-icon"></i>
            </button>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="mb-4 form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">
            {{ __('Remember Me') }}
        </label>
    </div>

    <!-- Add dynamic reCAPTCHA -->
    @recaptcha

    <div class="d-flex justify-content-between align-items-center mb-4">
        <button type="submit" class="btn btn-primary">
            {{ __('Login') }}
        </button>

        @if (Route::has('password.request'))
            <a class="text-primary" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </div>

    <hr>
    <div class="text-center mt-4">
        <p>{{ __('Don\'t have an account?') }}
            <a href="{{ route('register') }}" class="text-primary fw-bold ms-1">{{ __('Register') }}</a>
        </p>
    </div>
</form>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.add('d-none');
            eyeOffIcon.classList.remove('d-none');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('d-none');
            eyeOffIcon.classList.add('d-none');
        }
    }
</script>
@endsection