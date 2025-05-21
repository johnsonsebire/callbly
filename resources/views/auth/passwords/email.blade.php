@extends('layouts.auth')

@section('content')
<div class="text-center mb-10">
    <h1 class="text-dark fw-bolder mb-3">Forgot Password</h1>
    <div class="text-gray-500 fw-semibold fs-6">Enter your email to reset your password</div>
</div>

@if (session('status'))
<div class="alert alert-success d-flex align-items-center p-5 mb-10">
    <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
    <div class="d-flex flex-column">
        <h4 class="mb-1 text-success">Success</h4>
        <span>{{ session('status') }}</span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <i class="ki-outline ki-cross fs-2 text-success"></i>
    </button>
</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-10">
        <label for="email" class="form-label fw-bold">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-grid mb-10">
        <button type="submit" class="btn btn-primary">
            {{ __('Send Password Reset Link') }}
        </button>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}" class="link-primary fs-6 fw-bold">{{ __('Back to login') }}</a>
    </div>
</form>
@endsection