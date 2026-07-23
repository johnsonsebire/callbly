@extends('layouts.master')

@section('title', 'Purchase SMS Credits')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Welcome Banner Hero Card -->
                <div class="card mb-5 mb-xl-8 border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e293b 100%);">
                    <div class="card-body p-6 sm:p-8 text-white relative">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-6">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-60px symbol-lg-75px symbol-circle border border-2 border-cyan-400/40 p-1 shadow-inner" style="background: rgba(14, 165, 233, 0.15);">
                                    <div class="symbol-label font-extrabold text-cyan-400 fs-1 rounded-circle" style="background: #0ea5e9; color: #ffffff;">
                                        <i class="ki-outline ki-plus-square fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Purchase SMS Credits</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Top-Up Engine</span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 text-gray-400 fs-7 mt-2">
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-wallet fs-5 me-1 text-primary"></i>
                                            Balance: <strong class="text-white ms-1 me-2">{{ number_format(auth()->user()->sms_credits) }} credits</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-tag fs-5 me-1 text-success"></i>
                                            Rate: <strong class="text-white ms-1 me-2">{{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->getSmsRate(), 3) }}/SMS</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-badge fs-5 me-1 text-info"></i>
                                            Tier: <strong class="text-white ms-1">{{ auth()->user()->billingTier->name }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('wallet.purchase-sms') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-wallet me-1 fs-5"></i> Buy with Wallet Balance
                                </a>
                                <a href="{{ route('sms.billing-tier') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-tag me-1 fs-5"></i> View Billing Tiers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                        <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                        <span class="font-bold text-gray-800 fs-6">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Payment Methods Info Callout -->
                <div class="card border-0 shadow-sm mb-6 rounded-3 bg-light-primary border-start border-4 border-primary">
                    <div class="card-body p-5 d-flex align-items-start gap-4">
                        <i class="ki-outline ki-information-5 fs-2hx text-primary mt-1"></i>
                        <div class="flex-grow-1">
                            <h4 class="text-gray-900 font-bold fs-6 mb-1">Flexible Purchase Methods</h4>
                            <p class="text-gray-700 fs-7 mb-2">Refill your SMS credits instantly via direct online checkout or deduction from your Prepaid Wallet.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('wallet.purchase-sms') }}" class="btn btn-sm btn-primary font-bold shadow-xs px-3">
                                    <i class="ki-outline ki-wallet me-1 fs-6"></i> Deduct from Wallet Balance
                                </a>
                                <a href="{{ route('sms.billing-tier') }}" class="btn btn-sm btn-light-primary font-bold px-3">
                                    View Tier Rate Discounts &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @push('styles')
                <style>
                    .pkg-card {
                        background: #ffffff;
                        border-radius: 1.25rem !important;
                        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                        position: relative;
                        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04) !important;
                        border: 1px solid rgba(0, 0, 0, 0.04) !important;
                    }
                    
                    [data-bs-theme="dark"] .pkg-card {
                        background: #1e1e2d !important;
                        border-color: rgba(255, 255, 255, 0.06) !important;
                        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25) !important;
                    }
                    
                    .pkg-card:hover {
                        transform: translateY(-4px) !important;
                        box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.08) !important;
                    }
                </style>
                @endpush

                <!-- Packages Row -->
                <div class="row g-5 g-xl-6 mb-6">
                    <!-- Basic Package -->
                    <div class="col-md-4">
                        <div class="card pkg-card h-100 overflow-hidden" style="border-top: 4px solid #0284c7 !important;">
                            <div class="card-body p-6 text-center d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Starter Package</span>
                                        <span class="badge badge-light-primary font-bold fs-8 px-2.5 py-1 rounded-pill">Popular</span>
                                    </div>
                                    <h3 class="fs-3hx font-extrabold text-gray-900 mb-2">{{ auth()->user()->formatAmount(50) }}</h3>
                                    <div class="bg-light-soft rounded-3 p-3 mb-4">
                                        <span class="fs-2 font-extrabold text-primary d-block">{{ number_format(floor(50 / auth()->user()->getSmsRate())) }}</span>
                                        <span class="text-gray-500 font-semibold fs-8 uppercase tracking-wider">SMS Credits</span>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('payment.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="50">
                                        <input type="hidden" name="product_type" value="sms_credits">
                                        <button type="submit" class="btn btn-primary font-bold w-100 py-3 shadow-sm">
                                            <i class="ki-outline ki-credit-cart fs-4 me-2"></i>Buy Starter Package
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Standard Package -->
                    <div class="col-md-4">
                        <div class="card pkg-card h-100 overflow-hidden" style="border-top: 4px solid #10b981 !important;">
                            <div class="card-body p-6 text-center d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Growth Package</span>
                                        <span class="badge badge-light-success font-bold fs-8 px-2.5 py-1 rounded-pill">Best Value</span>
                                    </div>
                                    <h3 class="fs-3hx font-extrabold text-gray-900 mb-2">{{ auth()->user()->formatAmount(100) }}</h3>
                                    <div class="bg-light-soft rounded-3 p-3 mb-4">
                                        <span class="fs-2 font-extrabold text-success d-block">{{ number_format(floor(100 / auth()->user()->getSmsRate())) }}</span>
                                        <span class="text-gray-500 font-semibold fs-8 uppercase tracking-wider">SMS Credits</span>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('payment.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="100">
                                        <input type="hidden" name="product_type" value="sms_credits">
                                        <button type="submit" class="btn btn-success font-bold text-white w-100 py-3 shadow-sm">
                                            <i class="ki-outline ki-credit-cart fs-4 me-2"></i>Buy Growth Package
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Package -->
                    <div class="col-md-4">
                        <div class="card pkg-card h-100 overflow-hidden" style="border-top: 4px solid #8b5cf6 !important;">
                            <div class="card-body p-6 text-center d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <span class="text-gray-500 font-bold fs-8 uppercase tracking-wider">Volume Package</span>
                                        <span class="badge badge-light-info font-bold fs-8 px-2.5 py-1 rounded-pill">Tier Upgrade</span>
                                    </div>
                                    <h3 class="fs-3hx font-extrabold text-gray-900 mb-2">{{ auth()->user()->formatAmount(250) }}</h3>
                                    <div class="bg-light-soft rounded-3 p-3 mb-4">
                                        <span class="fs-2 font-extrabold text-info d-block">{{ number_format(floor(250 / auth()->user()->getSmsRate())) }}</span>
                                        <span class="text-gray-500 font-semibold fs-8 uppercase tracking-wider">SMS Credits</span>
                                    </div>
                                </div>
                                <div>
                                    <form action="{{ route('payment.initiate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="amount" value="250">
                                        <input type="hidden" name="product_type" value="sms_credits">
                                        <button type="submit" class="btn btn-info font-bold text-white w-100 py-3 shadow-sm">
                                            <i class="ki-outline ki-credit-cart fs-4 me-2"></i>Buy Volume Package
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom Amount Section -->
                <div class="card border-0 shadow-sm overflow-hidden mb-6" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-4">Custom Amount Top-Up</span>
                            <span class="text-gray-400 mt-1 fs-7">Enter a custom refill value to calculate exact credits</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <form action="{{ route('payment.initiate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_type" value="sms_credits">
                            
                            <div class="row align-items-center mb-6">
                                <label for="custom_amount" class="col-lg-3 col-form-label font-bold text-gray-800 fs-6">Amount in {{ auth()->user()->currency->code }}</label>
                                <div class="col-lg-9">
                                    <div class="input-group input-group-solid">
                                        <span class="input-group-text font-bold text-gray-700">{{ auth()->user()->currency->symbol }}</span>
                                        <input type="text" name="amount" id="custom_amount" class="form-control form-control-solid font-bold fs-5" 
                                            placeholder="0.00" required>
                                        <span class="input-group-text font-bold text-gray-700">{{ auth()->user()->currency->code }}</span>
                                    </div>
                                    <div class="form-text font-semibold text-primary mt-2" id="estimated-credits">Enter an amount to see estimated credits</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                    <i class="ki-outline ki-credit-cart fs-3 me-2"></i>Proceed to Checkout
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Trust Features Grid -->
                <div class="row g-4 mb-6">
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-white p-5 rounded-3 border border-gray-200 shadow-xs h-100 d-flex align-items-center gap-4">
                            <div class="symbol symbol-45px rounded-2xl bg-light-primary d-flex align-items-center justify-content-center">
                                <i class="ki-outline ki-shield-tick text-primary fs-2x"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-bold fs-6 mb-0.5">Secure Checkout</h4>
                                <span class="text-gray-500 fs-8 font-medium">Processed via Paystack encryption</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-white p-5 rounded-3 border border-gray-200 shadow-xs h-100 d-flex align-items-center gap-4">
                            <div class="symbol symbol-45px rounded-2xl bg-light-success d-flex align-items-center justify-content-center">
                                <i class="ki-outline ki-timer text-success fs-2x"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-bold fs-6 mb-0.5">Instant Refill</h4>
                                <span class="text-gray-500 fs-8 font-medium">Credits added to account live</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-white p-5 rounded-3 border border-gray-200 shadow-xs h-100 d-flex align-items-center gap-4">
                            <div class="symbol symbol-45px rounded-2xl bg-light-info d-flex align-items-center justify-content-center">
                                <i class="ki-outline ki-discount text-info fs-2x"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-bold fs-6 mb-0.5">Volume Tiers</h4>
                                <span class="text-gray-500 fs-8 font-medium">Higher top-ups qualify for lower rates</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-white p-5 rounded-3 border border-gray-200 shadow-xs h-100 d-flex align-items-center gap-4">
                            <div class="symbol symbol-45px rounded-2xl bg-light-warning d-flex align-items-center justify-content-center">
                                <i class="ki-outline ki-calendar-tick text-warning fs-2x"></i>
                            </div>
                            <div>
                                <h4 class="text-gray-900 font-bold fs-6 mb-0.5">No Expiration</h4>
                                <span class="text-gray-500 fs-8 font-medium">SMS credits never expire</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customAmountInput = document.getElementById('custom_amount');
        const estimatedCreditsText = document.getElementById('estimated-credits');
        const smsRate = {{ auth()->user()->getSmsRate() }};
        
        if (customAmountInput) {
            customAmountInput.addEventListener('input', function(e) {
                const amount = parseFloat(e.target.value) || 0;
                const estimatedCredits = Math.floor(amount / smsRate);
                
                if (amount > 0) {
                    estimatedCreditsText.textContent = `This will give you approximately ${estimatedCredits.toLocaleString()} SMS credits at your current rate.`;
                    if (amount >= 250) {
                        estimatedCreditsText.innerHTML += ' <span class="text-success font-bold"><i class="ki-outline ki-arrow-up"></i> Purchase qualifies for volume rate tier!</span>';
                    }
                } else {
                    estimatedCreditsText.textContent = 'Enter an amount to see estimated credits';
                }
            });
        }
    });
</script>
@endpush