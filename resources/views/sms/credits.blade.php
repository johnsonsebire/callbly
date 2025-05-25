@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Header Section -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body pt-9 pb-0">
                        <div class="d-flex flex-wrap flex-sm-nowrap">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <div class="symbol-label bg-light-primary text-primary fs-2 fw-bold">
                                        <i class="ki-outline ki-message-text-2 fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">SMS Credits</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-wallet fs-4 me-1"></i>
                                                Current Balance: <span class="fw-bold text-primary ms-1">{{ auth()->user()->sms_credits }} credits</span>
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-tag fs-4 me-1"></i>
                                                Tier: <span class="fw-bold text-primary ms-1">{{ auth()->user()->billingTier->name }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('sms.billing-tier') }}" class="btn btn-sm btn-light-primary">
                                            <i class="ki-outline ki-dollar fs-3"></i>
                                            View Billing Tiers
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success mb-5">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mb-5">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="alert alert-primary d-flex align-items-center p-5 mb-5">
                    <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-primary">Current Billing Tier: {{ auth()->user()->billingTier->name }}</h4>
                        <span>SMS Rate: <strong>{{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->getSmsRate(), 3) }}</strong> per SMS in {{ auth()->user()->currency->code }}</span>
                        <span class="mt-2">You can purchase credits in two ways:</span>
                        <ul class="mb-0">
                            <li>Direct payment via Paystack using the packages below</li>
                            <li>Using your wallet balance - <a href="{{ route('wallet.purchase-sms') }}" class="fw-bold">Click here to buy with wallet</a></li>
                        </ul>
                        <span class="mt-2">Purchase more credits at once to qualify for better pricing tiers. <a href="{{ route('sms.billing-tier') }}" class="fw-bold">View all tiers</a></span>
                    </div>
                </div>

                <!-- Credit Packages -->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!-- Basic Package -->
                    <div class="col-md-4">
                        <div class="card card-flush h-md-100">
                            <div class="card-header ribbon ribbon-top">
                                <div class="ribbon-label bg-primary">
                                    Popular
                                </div>
                                <h3 class="card-title">
                                    <span class="card-label fw-bold text-dark">Basic</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-10">
                                <div class="mb-5">
                                    <span class="text-dark fw-bold fs-2hx">{{ auth()->user()->formatAmount(50) }}</span>
                                </div>
                                <div class="fs-6 text-muted mb-5">This package gives you approximately 
                                    <span class="text-dark fw-bold">{{ floor(50 / auth()->user()->getSmsRate()) }}</span> 
                                    SMS credits at your current rate.
                                </div>
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('payment.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="50">
                                        <input type="hidden" name="product_type" value="sms_credits">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-credit-cart fs-2 me-2"></i>Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Standard Package -->
                    <div class="col-md-4">
                        <div class="card card-flush border border-2 border-primary h-md-100">
                            <div class="card-header ribbon ribbon-top">
                                <div class="ribbon-label bg-success">
                                    Best Value
                                </div>
                                <h3 class="card-title">
                                    <span class="card-label fw-bold text-dark">Standard</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-10">
                                <div class="mb-5">
                                    <span class="text-primary fw-bold fs-2hx">{{ auth()->user()->formatAmount(100) }}</span>
                                </div>
                                <div class="fs-6 text-muted mb-5">This package gives you approximately 
                                    <span class="text-dark fw-bold">{{ floor(100 / auth()->user()->getSmsRate()) }}</span> 
                                    SMS credits at your current rate.
                                </div>
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('payment.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="100">
                                        <input type="hidden" name="product_type" value="sms_credits">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-credit-cart fs-2 me-2"></i>Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Package -->
                    <div class="col-md-4">
                        <div class="card card-flush h-md-100">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <span class="card-label fw-bold text-dark">Premium</span>
                                </h3>
                            </div>
                            <div class="card-body text-center pt-10">
                                <div class="mb-5">
                                    <span class="text-dark fw-bold fs-2hx">{{ auth()->user()->formatAmount(250) }}</span>
                                </div>
                                <div class="fs-6 text-muted mb-5">This package gives you approximately 
                                    <span class="text-dark fw-bold">{{ floor(250 / auth()->user()->getSmsRate()) }}</span> 
                                    SMS credits at your current rate.
                                </div>
                                <div class="badge badge-light-success fs-7 fw-semibold mb-5">
                                    <i class="ki-outline ki-arrow-up fs-7 me-1"></i>
                                    May qualify for better billing tier!
                                </div>
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('payment.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="250">
                                        <input type="hidden" name="product_type" value="sms_credits">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-credit-cart fs-2 me-2"></i>Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Amount -->
                <div class="card card-flush h-lg-100 mb-5 mb-xl-10">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Custom Amount</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Purchase a specific amount of SMS credits</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        <form action="{{ route('payment.initiate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_type" value="sms_credits">
                            
                            <div class="row mb-6">
                                <label class="col-lg-3 col-form-label fw-semibold fs-6">Enter amount in {{ auth()->user()->currency->code }}</label>
                                <div class="col-lg-9">
                                    <div class="input-group input-group-solid">
                                        <span class="input-group-text">{{ auth()->user()->currency->symbol }}</span>
                                        <input type="text" name="amount" id="custom_amount" class="form-control form-control-solid" 
                                            placeholder="0.00" required>
                                        <span class="input-group-text">{{ auth()->user()->currency->code }}</span>
                                    </div>
                                    <div class="form-text" id="estimated-credits">Enter an amount to see estimated credits</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-credit-cart fs-3 me-2"></i>Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card card-flush shadow-sm h-lg-100 mb-5 mb-xl-10">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Payment Information</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Important details about your purchase</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="bg-light-primary rounded p-6 mb-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-primary">
                                                <i class="ki-outline ki-shield-tick fs-2 text-white"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-dark">Secure Transactions</h4>
                                            <span class="text-muted fw-semibold">Payments securely processed via Paystack</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-light-success rounded p-6">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-success">
                                                <i class="ki-outline ki-timer fs-2 text-white"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-dark">Instant Credit</h4>
                                            <span class="text-muted fw-semibold">Credits added to your account immediately</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light-info rounded p-6 mb-5">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-info">
                                                <i class="ki-outline ki-discount fs-2 text-white"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-dark">Volume Discounts</h4>
                                            <span class="text-muted fw-semibold">Larger purchases qualify for better rates</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-light-warning rounded p-6">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-warning">
                                                <i class="ki-outline ki-calendar-tick fs-2 text-white"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-dark">No Expiration</h4>
                                            <span class="text-muted fw-semibold">Your SMS credits never expire</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-stack flex-wrap pt-10">
                    <div class="fs-6 fw-semibold text-gray-700">Need assistance? <a href="#" class="link-primary fw-bold">Contact our support team</a></div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
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
                if (amount >= 250) {
                    estimatedCreditsText.innerHTML += ' <span class="text-success"><i class="ki-outline ki-arrow-up"></i> This purchase may qualify you for a better rate!</span>';
                }
            } else {
                estimatedCreditsText.textContent = 'Enter an amount to see estimated credits';
            }
        });
    });
</script>
@endsection