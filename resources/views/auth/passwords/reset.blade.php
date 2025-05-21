@extends('layouts.auth')

@section('content')
<div class="text-center mb-10">
    <h1 class="text-dark fw-bolder mb-3">Reset Password</h1>
    <div class="text-gray-500 fw-semibold fs-6">Enter your new password below</div>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-4">
        <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-bold">{{ __('New Password') }}</label>
        <div class="input-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password')">
                <i class="bi bi-eye" id="eye-icon-password"></i>
                <i class="bi bi-eye-slash d-none" id="eye-off-icon-password"></i>
            </button>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-text">Password must be at least 8 characters</div>
    </div>

    <div class="mb-6">
        <label for="password-confirm" class="form-label fw-bold">{{ __('Confirm Password') }}</label>
        <div class="input-group">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password-confirm')">
                <i class="bi bi-eye" id="eye-icon-password-confirm"></i>
                <i class="bi bi-eye-slash d-none" id="eye-off-icon-password-confirm"></i>
            </button>
        </div>
    </div>

    <div class="d-grid mb-10">
        <button type="submit" class="btn btn-primary">
            {{ __('Reset Password') }}
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