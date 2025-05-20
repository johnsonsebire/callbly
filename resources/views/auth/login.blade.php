<div class="card-title">@extends('layouts.auth')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4 class="text-center mb-0">{{ __('Login') }}</h4>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body p-4 p-md-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">
                                {{ __('Email Address') }} <span class="text-danger">*</span>
                            </label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div><div class="card-title"></div><div class="card-title"></div>
                            @enderror
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                {{ __('Password') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">
                                    <i class="bi bi-eye" id="eye-icon"></i>
                                    <i class="bi bi-eye-slash d-none" id="eye-off-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div><div class="card-title"></div><div class="card-title"></div>
                                @enderror
                            </div><div class="card-title"></div><div class="card-title"></div>
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="text-primary" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div><div class="card-title"></div><div class="card-title"></div>

                        <hr>
                        <div class="text-center mt-4">
                            <p>{{ __('Don\'t have an account?') }}
                                <a href="{{ route('register') }}" class="text-primary fw-bold ms-1">{{ __('Register') }}</a>
                            </p>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </form>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>

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