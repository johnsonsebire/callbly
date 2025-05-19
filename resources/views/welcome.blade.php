<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Callbly') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter selection:bg-blue-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ route('dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-blue-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <x-application-logo class="w-20 h-20" />
                </div>

                <div class="mt-16 text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Callbly</h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Your all-in-one communication platform for SMS, Voice Calls, USSD services, and Virtual Numbers.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Go to Dashboard</a>
                        @else
                            <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Get Started</a>
                            <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900">Log in <span aria-hidden="true">→</span></a>
                        @endauth
                    </div>
                </div>

                <div class="mt-16">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-900">SMS Services</h2>
                            <p class="mt-2 text-gray-600">Send single and bulk SMS messages with personalized sender IDs.</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-900">Voice Calls</h2>
                            <p class="mt-2 text-gray-600">Make automated and interactive phone calls with detailed analytics.</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-900">USSD Services</h2>
                            <p class="mt-2 text-gray-600">Create interactive USSD services for your business needs.</p>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h2 class="text-xl font-semibold text-gray-900">Virtual Numbers</h2>
                            <p class="mt-2 text-gray-600">Get dedicated phone numbers for your business communications.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
