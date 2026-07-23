@extends('layouts.auth')

@section('title', 'Set New Password')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-dark dark-text-white fw-bolder fs-2x mb-2">Set New Password</h1>
    <div class="text-gray-500 fw-medium fs-6">Please enter your new password below</div>
</div>

<form method="POST" action="{{ route('password.update') }}" class="form w-100">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-6">
        <label for="email" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Email Address') }} <span class="text-danger">*</span>
        </label>
        <input id="email" type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-6">
        <label for="password" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('New Password') }} <span class="text-danger">*</span>
        </label>
        <div class="position-relative">
            <input id="password" type="password" class="form-control form-control-solid @error('password') is-invalid @enderror pe-12" name="password" placeholder="Minimum 8 characters" required autocomplete="new-password">
            <button type="button" class="btn btn-sm btn-icon position-absolute translate-middle-y top-50 end-0 me-3" onclick="togglePasswordVisibility('password')">
                <i class="ki-outline ki-eye fs-2" id="eye-icon-password"></i>
                <i class="ki-outline ki-eye-slash fs-2 d-none" id="eye-off-icon-password"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger fs-7 mt-1">
                {{ $message }}
            </div>
        @enderror
        <div class="fs-8 text-gray-500 mt-1">Password must be at least 8 characters.</div>
    </div>

    <div class="mb-8">
        <label for="password-confirm" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Confirm New Password') }} <span class="text-danger">*</span>
        </label>
        <div class="position-relative">
            <input id="password-confirm" type="password" class="form-control form-control-solid pe-12" name="password_confirmation" placeholder="Repeat new password" required autocomplete="new-password">
            <button type="button" class="btn btn-sm btn-icon position-absolute translate-middle-y top-50 end-0 me-3" onclick="togglePasswordVisibility('password-confirm')">
                <i class="ki-outline ki-eye fs-2" id="eye-icon-password-confirm"></i>
                <i class="ki-outline ki-eye-slash fs-2 d-none" id="eye-off-icon-password-confirm"></i>
            </button>
        </div>
    </div>

    <div class="d-grid mb-8">
        <button type="submit" class="btn btn-primary py-3">
            <span class="indicator-label fs-6">{{ __('Reset Password') }}</span>
        </button>
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