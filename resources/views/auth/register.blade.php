@extends('layouts.auth')

@section('title', 'Create Account')

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

<div class="text-center mb-6">
    <h1 class="text-dark dark-text-white fw-bolder fs-2x mb-2">Get Started Free</h1>
    <div class="text-gray-500 fw-medium fs-6">Create your Callbly account in seconds</div>
</div>

<form method="POST" action="{{ route('register') }}" class="form w-100">
    @csrf

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <label for="name" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
                {{ __('Full Name') }} <span class="text-danger">*</span>
            </label>
            <input id="name" type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="John Doe" required autocomplete="name" autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="phone" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
                {{ __('Phone Number') }} <span class="text-danger">*</span>
            </label>
            <input id="phone" type="text" class="form-control form-control-solid @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="+233201234567" required>
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-4">
        <label for="email" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Work Email') }} <span class="text-danger">*</span>
        </label>
        <input id="email" type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? request('email') }}" placeholder="name@company.com" required autocomplete="email" {{ request('email') ? 'readonly' : '' }}>
        @if(request('email'))
            <div class="fs-7 text-info mt-1">This email matches your team invitation.</div>
        @endif
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="company_name" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Company / Brand Name') }} <span class="text-danger">*</span>
        </label>
        <input id="company_name" type="text" class="form-control form-control-solid @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" placeholder="Acme Inc." required>
        @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Password') }} <span class="text-danger">*</span>
        </label>
        <div class="position-relative">
            <input id="password" type="password" class="form-control form-control-solid @error('password') is-invalid @enderror pe-12" name="password" placeholder="Minimum 8 characters" required autocomplete="new-password">
            <button type="button" class="btn btn-sm btn-icon position-absolute translate-middle-y top-50 end-0 me-3" onclick="togglePasswordVisibility('password')">
                <i class="ki-outline ki-eye fs-2" id="eye-icon-password"></i>
                <i class="ki-outline ki-eye-slash fs-2 d-none" id="eye-off-icon-password"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger fs-7 mt-1">{{ $message }}</div>
        @enderror
        <div class="fs-8 text-gray-500 mt-1">Must be at least 8 characters with uppercase, lowercase, numbers & symbols.</div>
    </div>

    <div class="mb-6">
        <label for="password-confirm" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Confirm Password') }} <span class="text-danger">*</span>
        </label>
        <div class="position-relative">
            <input id="password-confirm" type="password" class="form-control form-control-solid pe-12" name="password_confirmation" placeholder="Repeat your password" required autocomplete="new-password">
            <button type="button" class="btn btn-sm btn-icon position-absolute translate-middle-y top-50 end-0 me-3" onclick="togglePasswordVisibility('password-confirm')">
                <i class="ki-outline ki-eye fs-2" id="eye-icon-password-confirm"></i>
                <i class="ki-outline ki-eye-slash fs-2 d-none" id="eye-off-icon-password-confirm"></i>
            </button>
        </div>
    </div>

    <!-- Add dynamic reCAPTCHA -->
    @recaptcha

    <div class="d-grid mb-6">
        <button type="submit" class="btn btn-primary py-3">
            <span class="indicator-label fs-6">{{ __('Create Account') }}</span>
        </button>
    </div>

    <div class="text-center text-gray-500 fs-7 fw-medium">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" class="link-primary fw-bold ms-1">{{ __('Sign In') }}</a>
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