@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="text-center mb-8">
    <div class="symbol symbol-60px symbol-circle bg-light-primary mb-4 p-3 d-inline-flex flex-center">
        <i class="ki-outline ki-sms fs-2x text-primary"></i>
    </div>
    <h1 class="text-dark dark-text-white fw-bolder fs-2x mb-2">Verify Your Email</h1>
    <div class="text-gray-500 fw-medium fs-6">Please check your inbox to activate your Callbly account</div>
</div>

@if (session('status') == 'verification-link-sent')
<div class="alert alert-success d-flex align-items-center p-4 mb-6 rounded-3" role="alert">
    <i class="ki-outline ki-shield-tick fs-2x text-success me-3"></i>
    <div class="fs-7 text-gray-700 dark-text-gray-300">
        A fresh verification link has been sent to your email address.
    </div>
</div>
@endif

<div class="text-gray-600 dark-text-gray-300 fs-6 leading-relaxed mb-6 text-center">
    Before proceeding, please check your email for a verification link. 
    If you did not receive the email, click the button below to request another.
</div>

<form method="POST" action="{{ route('verification.send') }}" class="form w-100 mb-6">
    @csrf
    <div class="d-grid">
        <button type="submit" class="btn btn-primary py-3">
            <span class="indicator-label fs-6">{{ __('Resend Verification Email') }}</span>
        </button>
    </div>
</form>

<div class="text-center text-gray-500 fs-7 fw-medium">
    Need help? <a href="mailto:support@callbly.com" class="link-primary fw-bold ms-1">Contact Support</a>
</div>
@endsection