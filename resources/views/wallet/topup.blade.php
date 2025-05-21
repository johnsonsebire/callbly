@extends('layouts.master')

@section('title', 'Top Up Wallet')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('wallet.index') }}">Wallet</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Top Up</li>
                            </ol>
                        </nav>
                        
                        <h1 class="h2 mb-4">Top Up Your Wallet</h1>
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-lg-8">
                        <!--begin::Card-->
                        <div class="card card-flush mb-5">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Top Up Details</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <form action="{{ route('wallet.process-topup') }}" method="POST" id="topupForm">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label for="currentBalance" class="form-label">Current Balance</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $user->currency->symbol }}</span>
                                            <input type="text" class="form-control form-control-lg" value="{{ number_format($user->wallet->balance, 2) }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="amount" class="form-label">Amount to Add</label>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $user->currency->symbol }}</span>
                                            <input type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                                id="amount" name="amount" placeholder="Enter amount" step="0.01" min="5" 
                                                value="{{ old('amount') }}" required>
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="form-text text-muted">Minimum amount: {{ $user->currency->symbol }}5.00</small>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Payment Method</label>
                                        
                                        <div class="payment-methods">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div class="form-check payment-method-card">
                                                        <input class="form-check-input" type="radio" name="payment_method" id="cardPayment" value="card" checked>
                                                        <label class="form-check-label" for="cardPayment">
                                                            <div class="d-flex align-items-center">
                                                                <div class="payment-icon me-2">
                                                                    <i class="bi bi-credit-card fs-3"></i>
                                                                </div>
                                                                <div>
                                                                    <span>Credit/Debit Card</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-check payment-method-card">
                                                        <input class="form-check-input" type="radio" name="payment_method" id="mobileMoneyPayment" value="mobile_money">
                                                        <label class="form-check-label" for="mobileMoneyPayment">
                                                            <div class="d-flex align-items-center">
                                                                <div class="payment-icon me-2">
                                                                    <i class="bi bi-phone fs-3"></i>
                                                                </div>
                                                                <div>
                                                                    <span>Mobile Money</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-check payment-method-card">
                                                        <input class="form-check-input" type="radio" name="payment_method" id="bankTransferPayment" value="bank_transfer">
                                                        <label class="form-check-label" for="bankTransferPayment">
                                                            <div class="d-flex align-items-center">
                                                                <div class="payment-icon me-2">
                                                                    <i class="bi bi-bank fs-3"></i>
                                                                </div>
                                                                <div>
                                                                    <span>Bank Transfer</span>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="notice d-flex bg-light-info rounded border-info border border-dashed p-6">
                                            <i class="ki-outline ki-information-5 fs-2tx text-info me-4"></i>
                                            <div class="d-flex flex-stack flex-grow-1">
                                                <div class="fw-semibold">
                                                    <h4 class="text-gray-900 fw-bold">Important</h4>
                                                    <div class="fs-6 text-gray-700">Your wallet will be credited immediately after successful payment. Please note that transaction fees may apply depending on your payment method.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">Proceed to Payment</button>
                                        <a href="{{ route('wallet.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    
                    <div class="col-lg-4">
                        <!--begin::Card-->
                        <div class="card card-flush mb-5">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bold">Top Up Summary</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column">
                                    <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                                        <span>Current Balance:</span>
                                        <span>{{ $user->currency->symbol }}{{ number_format($user->wallet->balance, 2) }}</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                                        <span>Amount to Add:</span>
                                        <span id="summaryAmount">{{ $user->currency->symbol }}0.00</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between py-2 font-weight-bold">
                                        <span>New Balance:</span>
                                        <span id="summaryNewBalance" class="text-success">{{ $user->currency->symbol }}{{ number_format($user->wallet->balance, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        
                        <!--begin::Card-->
                        <div class="card card-flush mb-5">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bold">Need Help?</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <p>If you have any questions about topping up your wallet, please contact our support team.</p>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-chat-dots me-1"></i> Contact Support
                                </a>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection

@section('scripts')
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
        
        amountInput.addEventListener('input', updateSummary);
        updateSummary();
    });
</script>
