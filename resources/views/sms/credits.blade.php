@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Buy SMS Credits</h1>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Current SMS Credits</p>
                    <p class="text-2xl font-bold">{{ auth()->user()->sms_credits }}</p>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Current Billing Tier: {{ auth()->user()->billingTier->name }}</h3>
                        <p class="text-sm text-blue-700 mt-1">SMS Rate: {{ auth()->user()->formatAmount(auth()->user()->getSmsRate()) }} per SMS in {{ auth()->user()->currency->code }}</p>
                        <p class="text-xs text-blue-600 mt-1">Purchase more credits at once to qualify for better pricing tiers. <a href="{{ route('sms.billing-tier') }}" class="underline">View all tiers</a></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Package -->
                <div class="border border-gray-200 rounded-lg p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Basic</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold py-1 px-2 rounded">Popular</span>
                    </div>
                    <div class="mb-4">
                        <span class="text-3xl font-bold">{{ auth()->user()->formatAmount(50) }}</span>
                    </div>
                    <p class="text-gray-600 mb-4 text-sm">This package gives you approximately {{ floor(50 / auth()->user()->getSmsRate()) }} SMS credits at your current rate.</p>
                    <form action="{{ route('payment.initiate') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="amount" value="50">
                        <input type="hidden" name="product_type" value="sms_credits">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buy Now
                        </button>
                    </form>
                </div>

                <!-- Standard Package -->
                <div class="border border-gray-200 rounded-lg p-6 flex flex-col relative">
                    <div class="absolute -top-3 -right-3">
                        <span class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-xs font-bold py-1 px-3 rounded-full">Best Value</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Standard</h3>
                    </div>
                    <div class="mb-4">
                        <span class="text-3xl font-bold">{{ auth()->user()->formatAmount(100) }}</span>
                    </div>
                    <p class="text-gray-600 mb-4 text-sm">This package gives you approximately {{ floor(100 / auth()->user()->getSmsRate()) }} SMS credits at your current rate.</p>
                    <form action="{{ route('payment.initiate') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="amount" value="100">
                        <input type="hidden" name="product_type" value="sms_credits">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded">
                            Buy Now
                        </button>
                    </form>
                </div>

                <!-- Premium Package -->
                <div class="border border-gray-200 rounded-lg p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Premium</h3>
                    </div>
                    <div class="mb-4">
                        <span class="text-3xl font-bold">{{ auth()->user()->formatAmount(250) }}</span>
                    </div>
                    <p class="text-gray-600 mb-4 text-sm">This package gives you approximately {{ floor(250 / auth()->user()->getSmsRate()) }} SMS credits at your current rate.</p>
                    <p class="text-green-600 text-xs mb-4">This purchase may qualify you for a better billing tier!</p>
                    <form action="{{ route('payment.initiate') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="amount" value="250">
                        <input type="hidden" name="product_type" value="sms_credits">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buy Now
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium mb-4">Custom Amount</h3>
                <form action="{{ route('payment.initiate') }}" method="POST" class="max-w-md">
                    @csrf
                    <input type="hidden" name="product_type" value="sms_credits">
                    <div class="mb-4">
                        <label for="custom_amount" class="block text-sm font-medium text-gray-700 mb-2">Enter amount in {{ auth()->user()->currency->code }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ auth()->user()->currency->symbol }}</span>
                            </div>
                            <input type="text" name="amount" id="custom_amount" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ auth()->user()->currency->code }}</span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500" id="estimated-credits">Enter an amount to see estimated credits</p>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-medium mb-2">Payment Information</h3>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Secure payments processed via Paystack</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Credits are added to your account instantly after payment confirmation</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Larger purchases qualify for better rates automatically</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>SMS credits never expire</span>
                    </li>
                </ul>
            </div>

            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Need assistance? <a href="#" class="text-blue-600 hover:underline">Contact our support team</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customAmountInput = document.getElementById('custom_amount');
        const estimatedCreditsText = document.getElementById('estimated-credits');
        const smsRate = {{ auth()->user()->getSmsRate() }};
        
        customAmountInput.addEventListener('input', function(e) {
            const amount = parseFloat(e.target.value) || 0;
            const estimatedCredits = Math.floor(amount / smsRate);
            
            if (amount > 0) {
                estimatedCreditsText.textContent = `This will give you approximately ${estimatedCredits} SMS credits at your current rate.`;
            } else {
                estimatedCreditsText.textContent = 'Enter an amount to see estimated credits';
            }
        });
    });
</script>
@endsection