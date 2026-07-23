@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<!-- Team Invitation Message -->
@if (session('message'))
<div class="alert alert-info d-flex align-items-center p-4 mb-6 rounded-3">
    <i class="ki-outline ki-information-2 fs-2x text-info me-3"></i>
    <div>
        <h5 class="mb-1 text-info fw-bold">Team Invitation</h5>
        <div class="fs-7 text-gray-700 dark-text-gray-300">{{ session('message') }}</div>
    </div>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger d-flex align-items-center p-4 mb-6 rounded-3">
    <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
    <div class="fs-7 text-danger">{{ session('error') }}</div>
</div>
@endif

<div class="text-center mb-8">
    <h1 class="text-dark dark-text-white fw-bolder fs-2x mb-2">Welcome Back!</h1>
    <div class="text-gray-500 fw-medium fs-6">Sign in to manage your Callbly account & APIs</div>
</div>

<form method="POST" action="{{ route('login') }}" class="form w-100">
    @csrf

    <div class="mb-6">
        <label for="email" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Email Address') }} <span class="text-danger">*</span>
        </label>
        <input id="email" type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? request('email') }}" placeholder="name@company.com" required autocomplete="email" autofocus>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-6">
        <div class="d-flex flex-stack mb-2">
            <label for="password" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7 mb-0">
                {{ __('Password') }} <span class="text-danger">*</span>
            </label>
            @if (Route::has('password.request'))
                <a class="link-primary fs-7 fw-bold" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>
        <div class="position-relative">
            <input id="password" type="password" class="form-control form-control-solid @error('password') is-invalid @enderror pe-12" name="password" placeholder="Enter your password" required autocomplete="current-password">
            <button type="button" class="btn btn-sm btn-icon position-absolute translate-middle-y top-50 end-0 me-3" onclick="togglePasswordVisibility()">
                <i class="ki-outline ki-eye fs-2" id="eye-icon"></i>
                <i class="ki-outline ki-eye-slash fs-2 d-none" id="eye-off-icon"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger fs-7 mt-1">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-8">
        <div class="form-check form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-gray-700 dark-text-gray-300 fs-7" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>
    </div>

    <div class="d-grid mb-8">
        <button type="submit" class="btn btn-primary py-3">
            <span class="indicator-label fs-6">{{ __('Sign In') }}</span>
        </button>
    </div>

    <div class="text-center text-gray-500 fs-7 fw-medium">
        {{ __('Don\'t have an account?') }}
        <a href="{{ route('register') }}" class="link-primary fw-bold ms-1">{{ __('Create Account') }}</a>
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