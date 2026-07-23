@extends('layouts.master')

@section('title', 'Top Up Wallet Balance')

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
                                        <i class="ki-outline ki-plus-circle fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Top Up Wallet Balance</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Instant Funding</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Add funds to your account wallet using card, mobile money, or instant bank transfer.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('wallet.index') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Wallet
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                        <div>
                            <span class="font-bold text-gray-900 fs-6">Validation Errors</span>
                            <ul class="mb-0 fs-7 text-gray-700 ps-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="row g-5 g-xl-8">
                    <!-- Top Up Form Column -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Payment Details</span>
                                    <span class="text-gray-400 mt-1 fs-7">Specify top-up amount and preferred payment method</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form action="{{ route('wallet.process-topup') }}" method="POST" id="topupForm">
                                    @csrf
                                    
                                    <!-- Current Balance Field -->
                                    <div class="mb-6">
                                        <label class="form-label font-bold text-gray-800 fs-6 mb-2">Current Wallet Balance</label>
                                        <div class="input-group input-group-solid rounded-3">
                                            <span class="input-group-text font-bold text-gray-700">{{ $user->currency->symbol }}</span>
                                            <input type="text" class="form-control form-control-solid font-mono font-bold text-gray-900 fs-5" value="{{ number_format($user->wallet->balance, 2) }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Amount Input -->
                                    <div class="mb-6">
                                        <label for="amount" class="form-label font-bold text-gray-800 fs-6 mb-2">Amount to Add</label>
                                        <div class="input-group input-group-solid rounded-3">
                                            <span class="input-group-text font-bold text-gray-700">{{ $user->currency->symbol }}</span>
                                            <input type="number" class="form-control form-control-solid font-mono font-bold text-gray-900 fs-5 @error('amount') is-invalid @enderror" 
                                                id="amount" name="amount" placeholder="Enter amount" step="0.01" min="5" 
                                                value="{{ old('amount') }}" required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-gray-400 fs-8 mt-1.5 d-block">Minimum top-up amount: {{ $user->currency->symbol }}5.00</small>
                                    </div>
                                    
                                    <!-- Payment Method Selection -->
                                    <div class="mb-6">
                                        <label class="form-label font-bold text-gray-800 fs-6 mb-3">Select Payment Method</label>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="payment-method-card d-flex align-items-center p-4 border rounded-3 cursor-pointer h-100" for="cardPayment">
                                                    <input class="form-check-input me-3 flex-shrink-0" type="radio" name="payment_method" id="cardPayment" value="card" checked />
                                                    <div class="symbol symbol-35px me-3 flex-shrink-0">
                                                        <span class="symbol-label bg-light-primary rounded-circle">
                                                            <i class="ki-outline ki-credit-cart fs-3 text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <span class="font-bold text-gray-900 fs-7">Credit/Debit Card</span>
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="payment-method-card d-flex align-items-center p-4 border rounded-3 cursor-pointer h-100" for="mobileMoneyPayment">
                                                    <input class="form-check-input me-3 flex-shrink-0" type="radio" name="payment_method" id="mobileMoneyPayment" value="mobile_money" />
                                                    <div class="symbol symbol-35px me-3 flex-shrink-0">
                                                        <span class="symbol-label bg-light-success rounded-circle">
                                                            <i class="ki-outline ki-phone fs-3 text-success"></i>
                                                        </span>
                                                    </div>
                                                    <span class="font-bold text-gray-900 fs-7">Mobile Money</span>
                                                </label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="payment-method-card d-flex align-items-center p-4 border rounded-3 cursor-pointer h-100" for="bankTransferPayment">
                                                    <input class="form-check-input me-3 flex-shrink-0" type="radio" name="payment_method" id="bankTransferPayment" value="bank_transfer" />
                                                    <div class="symbol symbol-35px me-3 flex-shrink-0">
                                                        <span class="symbol-label bg-light-info rounded-circle">
                                                            <i class="ki-outline ki-bank fs-3 text-info"></i>
                                                        </span>
                                                    </div>
                                                    <span class="font-bold text-gray-900 fs-7">Bank Transfer</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Notice Box -->
                                    <div class="alert alert-primary border border-primary/20 d-flex align-items-center p-4 rounded-3 mb-6 shadow-xs">
                                        <i class="ki-outline ki-information-5 fs-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0.5 font-bold text-primary fs-7">Instant Credit Processing</h6>
                                            <span class="fs-8 text-gray-700 font-medium">Your wallet is credited instantly upon payment confirmation. Standard processing rules apply.</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-3 mt-6">
                                        <a href="{{ route('wallet.index') }}" class="btn btn-light font-bold px-5">Cancel</a>
                                        <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                            <i class="ki-outline ki-check me-2 fs-4"></i>Proceed to Payment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Live Summary Sidebar Column -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-top: 4px solid #10b981 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Top Up Summary</span>
                                    <span class="text-gray-400 mt-1 fs-8">Real-time balance projection</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2">
                                <div class="d-flex flex-column gap-3 fs-7">
                                    <div class="d-flex justify-content-between p-3 bg-light-soft rounded-3">
                                        <span class="text-gray-600 font-medium">Current Balance:</span>
                                        <span class="font-mono text-gray-900 font-bold">{{ $user->currency->symbol }}{{ number_format($user->wallet->balance, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between p-3 bg-light-soft rounded-3">
                                        <span class="text-gray-600 font-medium">Amount to Add:</span>
                                        <span id="summaryAmount" class="font-mono text-primary font-bold">{{ $user->currency->symbol }}0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between p-3 bg-light-success rounded-3 border border-success/20">
                                        <span class="text-success font-bold">New Projected Balance:</span>
                                        <span id="summaryNewBalance" class="font-mono text-success font-extrabold fs-6">{{ $user->currency->symbol }}{{ number_format($user->wallet->balance, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm overflow-hidden bg-light-soft" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0 bg-transparent">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-6">Need Assistance?</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 fs-7 text-gray-600">
                                <p class="mb-4">Have questions about billing rates or payment options? Our support desk is available 24/7.</p>
                                <a href="mailto:support@callbly.com" class="btn btn-outline btn-outline-primary btn-sm font-bold w-100">
                                    <i class="ki-outline ki-sms me-1 fs-5"></i> Contact Billing Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .payment-method-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0 !important;
        transition: all 0.2s ease-in-out;
        border-radius: 0.85rem !important;
    }
    
    .payment-method-card:hover {
        border-color: #0284c7 !important;
        background-color: #f8fafc;
    }

    .payment-method-card:has(input[type="radio"]:checked) {
        border-color: #0284c7 !important;
        background-color: rgba(2, 132, 199, 0.04);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.08);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const summaryAmount = document.getElementById('summaryAmount');
        const summaryNewBalance = document.getElementById('summaryNewBalance');
        const currentBalance = {{ $user->wallet->balance }};
        const currencySymbol = '{{ $user->currency->symbol }}';
        
        function updateSummary() {
            const amount = parseFloat(amountInput.value) || 0;
            const newBalance = currentBalance + amount;
            
            summaryAmount.textContent = currencySymbol + amount.toFixed(2);
            summaryNewBalance.textContent = currencySymbol + newBalance.toFixed(2);
        }
        
        if (amountInput) {
            amountInput.addEventListener('input', updateSummary);
            updateSummary();
        }
    });
</script>
@endpush
