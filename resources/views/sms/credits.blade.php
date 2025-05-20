@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2>SMS Credits</h2>
                    <p class="text-muted">Purchase SMS credits to send messages</p>
                </div>
                <div class="text-end">
                    <p class="text-muted mb-0">Current Balance</p>
                    <h3 class="mb-0">{{ auth()->user()->sms_credits }} <span class="fs-6">credits</span></h3>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="alert alert-info mb-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <i class="fas fa-info-circle fa-2x"></i>
            </div>
            <div class="col">
                <h5 class="mb-1">Current Billing Tier: {{ auth()->user()->billingTier->name }}</h5>
                <p class="mb-1">SMS Rate: {{ auth()->user()->formatAmount(auth()->user()->getSmsRate()) }} per SMS in {{ auth()->user()->currency->code }}</p>
                <p class="mb-0 small">Purchase more credits at once to qualify for better pricing tiers. <a href="{{ route('sms.billing-tier') }}">View all tiers</a></p>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <!-- Basic Package -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Basic</h5>
                        <span class="badge bg-primary">Popular</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="display-6">{{ auth()->user()->formatAmount(50) }}</span>
                    </div>
                    <p class="text-muted mb-4">This package gives you approximately {{ floor(50 / auth()->user()->getSmsRate()) }} SMS credits at your current rate.</p>
                    <form action="{{ route('payment.initiate') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="amount" value="50">
                        <input type="hidden" name="product_type" value="sms_credits">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Standard Package -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow position-relative">
                <div class="position-absolute" style="top: -10px; right: -10px;">
                    <span class="badge bg-success py-2 px-3">Best Value</span>
                </div>
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Standard</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="display-6">{{ auth()->user()->formatAmount(100) }}</span>
                    </div>
                    <p class="text-muted mb-4">This package gives you approximately {{ floor(100 / auth()->user()->getSmsRate()) }} SMS credits at your current rate.</p>
                    <form action="{{ route('payment.initiate') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="amount" value="100">
                        <input type="hidden" name="product_type" value="sms_credits">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Premium Package -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Premium</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <span class="display-6">{{ auth()->user()->formatAmount(250) }}</span>
                    </div>
                    <p class="text-muted mb-4">This package gives you approximately {{ floor(250 / auth()->user()->getSmsRate()) }} SMS credits at your current rate.</p>
                    <p class="text-success small mb-4">
                        <i class="fas fa-arrow-circle-up me-1"></i>
                        This purchase may qualify you for a better billing tier!
                    </p>
                    <form action="{{ route('payment.initiate') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="amount" value="250">
                        <input type="hidden" name="product_type" value="sms_credits">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Buy Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5 border-0 shadow-sm">
        <div class="card-header bg-light">
            <div class="card-title">
                <h5 class="mb-0">Custom Amount</h5>
            </div>
            </div>
        <div class="card-body">
            <form action="{{ route('payment.initiate') }}" method="POST" class="row g-3">
                @csrf
                <input type="hidden" name="product_type" value="sms_credits">
                
                <div class="col-md-6">
                    <label for="custom_amount" class="form-label">Enter amount in {{ auth()->user()->currency->code }}</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ auth()->user()->currency->symbol }}</span>
                        <input type="text" name="amount" id="custom_amount" class="form-control" 
                               placeholder="0.00" required>
                        <span class="input-group-text">{{ auth()->user()->currency->code }}</span>
                    </div>
                    <p class="form-text" id="estimated-credits">Enter an amount to see estimated credits</p>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-light">
            <div class="card-title">
            <h5 class="mb-0">Payment Information</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-lock text-success me-2"></i>
                            <span>Secure payments processed via Paystack</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-bolt text-success me-2"></i>
                            <span>Credits are added to your account instantly</span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-chart-line text-success me-2"></i>
                            <span>Larger purchases qualify for better rates</span>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-infinity text-success me-2"></i>
                            <span>SMS credits never expire</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-4">
        <p class="mb-0">Need assistance? <a href="#" class="text-primary">Contact our support team</a></p>
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
                if (amount >= 250) {
                    estimatedCreditsText.innerHTML += ' <span class="text-success"><i class="fas fa-arrow-circle-up"></i> This purchase may qualify you for a better rate!</span>';
                }
            } else {
                estimatedCreditsText.textContent = 'Enter an amount to see estimated credits';
            }
        });
    });
</script>
@endsection