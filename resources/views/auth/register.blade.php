@extends('layouts.auth')

@section('title', 'Create Account')

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
    <h1 class="fs-2 fw-bold mb-2">Create Your Account</h1>
    <p class="text-muted">Fill in the form below to get started</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label fw-bold">
            {{ __('Name') }} <span class="text-danger">*</span>
        </label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-bold">
            {{ __('Email Address') }} <span class="text-danger">*</span>
        </label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? request('email') }}" required autocomplete="email" {{ request('email') ? 'readonly' : '' }}>
        @if(request('email'))
            <div class="form-text">This email address matches your team invitation.</div>
        @endif
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label fw-bold">
            {{ __('Phone Number') }} <span class="text-danger">*</span>
        </label>
        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="company_name" class="form-label fw-bold">
            {{ __('Company Name') }} <span class="text-danger">*</span>
        </label>
        <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required>
        @error('company_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-bold">
            {{ __('Password') }} <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <button type="button" class="btn btn-outline-secondary border border-secondary" onclick="togglePasswordVisibility('password')">
                <i class="bi bi-eye" id="eye-icon-password"></i>
                <i class="bi bi-eye-slash d-none" id="eye-off-icon-password"></i>
            </button>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-text">Password must be at least 8 characters and contain letters (both uppercase and lowercase), numbers, and symbols.</div>
    </div>

    <div class="mb-4">
        <label for="password-confirm" class="form-label fw-bold">
            {{ __('Confirm Password') }} <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            <button type="button" class="btn btn-outline-secondary border border-secondary" onclick="togglePasswordVisibility('password-confirm')">
                <i class="bi bi-eye" id="eye-icon-password-confirm"></i>
                <i class="bi bi-eye-slash d-none" id="eye-off-icon-password-confirm"></i>
            </button>
        </div>
    </div>

    <!-- Add dynamic reCAPTCHA -->
    @recaptcha

    <div class="d-grid mb-4">
        <button type="submit" class="btn btn-primary py-2">
            {{ __('Register') }}
        </button>
    </div>

    <div class="text-center">
        <p>{{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="text-primary fw-bold ms-1">{{ __('Login') }}</a>
        </p>
    </div>
</form>

<script>
    function togglePasswordVisibility(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);
        const eyeOffIcon = document.getElementById(`eye-off-icon-${fieldId}`);
        
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