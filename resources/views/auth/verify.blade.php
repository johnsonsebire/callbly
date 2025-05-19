@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 rounded-lg shadow-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">{{ __('Verify Your Email Address') }}</h2>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        <div class="mb-6">
            <p class="text-gray-700">
                {{ __('Before proceeding, please check your email for a verification link. If you did not receive the email,') }}
            </p>

            <form class="mt-4" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ __('Click here to request another') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection