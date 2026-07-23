@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-dark dark-text-white fw-bolder fs-2x mb-2">Forgot Password?</h1>
    <div class="text-gray-500 fw-medium fs-6">Enter your registered email address to receive a password reset link</div>
</div>

@if (session('status'))
<div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3">
    <i class="ki-outline ki-shield-tick fs-2x text-success me-3"></i>
    <div class="d-flex flex-column">
        <h5 class="mb-1 text-success fw-bold">Success</h5>
        <div class="fs-7 text-gray-700 dark-text-gray-300">{{ session('status') }}</div>
    </div>
</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="form w-100">
    @csrf

    <div class="mb-6">
        <label for="email" class="form-label fw-bold text-gray-800 dark-text-gray-200 fs-7">
            {{ __('Email Address') }} <span class="text-danger">*</span>
        </label>
        <input id="email" type="email" class="form-control form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autocomplete="email" autofocus>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-grid mb-6">
        <button type="submit" class="btn btn-primary py-3">
            <span class="indicator-label fs-6">{{ __('Send Reset Link') }}</span>
        </button>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="link-primary fs-7 fw-bold flex-center">
            <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Sign In
        </a>
    </div>
</form>
@endsection