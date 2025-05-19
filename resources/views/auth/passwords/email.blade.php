@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 rounded-lg shadow-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Reset Password') }}</h2>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>

            <div class="text-center text-sm">
                <a class="text-blue-500 hover:text-blue-800" href="{{ route('login') }}">
                    {{ __('Back to Login') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection