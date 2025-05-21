@extends('layouts.master')

@section('title', 'Purchase SMS Credits')

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
                                <li class="breadcrumb-item active" aria-current="page">Purchase SMS Credits</li>
                            </ol>
                        </nav>
                        
                        <h1 class="h2 mb-4">Purchase SMS Credits</h1>
                        
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
                                    <h2 class="fw-bold">SMS Credits Purchase</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <form action="{{ route('wallet.process-purchase-sms') }}" method="POST" id="purchaseSmsForm">
                                    @csrf
                                    
                                    <div class="alert alert-info mb-4">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <i class="ki-outline ki-wallet fs-2tx text-info"></i>
                                            </div>
                                            <div>
                                                <h5 class="alert-heading">Wallet Balance</h5>
                                                <p class="mb-0">Your current wallet balance is {{ $user->currency->symbol }}{{ number_format($wallet->balance, 2) }}</p>
                                                <p class="mb-0">You can purchase up to {{ number_format($estimatedCredits) }} SMS credits at {{ $user->currency->symbol }}{{ number_format($smsRate, 3) }} per credit.</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="credits" class="form-label fw-semibold fs-6 mb-2">Number of SMS Credits</label>
                                        <div class="input-group">
                                            <input type="number" 
                                                class="form-control form-control-lg @error('credits') is-invalid @enderror" 
                                                id="credits" 
                                                name="credits" 
                                                min="1" 
                                                max="{{ $estimatedCredits }}"
                                                value="{{ old('credits', 100) }}"
                                                required>
                                            <span class="input-group-text">Credits</span>
                                        </div>
                                        @error('credits')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div id="costCalculation" class="form-text mt-2">
                                            Total Cost: <strong>{{ $user->currency->symbol }}<span id="totalCost">{{ number_format(100 * $smsRate, 2) }}</span></strong>
                                        </div>
                                    </div>
                                    
                                    <div class="separator separator-dashed my-8"></div>

                                    <div class="mb-4">
                                        <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                            <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                                            <div class="d-flex flex-stack flex-grow-1">
                                                <div class="fw-semibold">
                                                    <h4 class="text-gray-900 fw-bold">Important</h4>
                                                    <div class="fs-6 text-gray-700">This action will deduct funds from your wallet balance. SMS credits purchases are non-refundable.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('wallet.index') }}" class="btn btn-light me-3">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Purchase SMS Credits</button>
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
                                    <h2 class="fw-bold">Purchase Summary</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column">
                                    <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                                        <span>Current Balance:</span>
                                        <span>{{ $user->currency->symbol }}{{ number_format($wallet->balance, 2) }}</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                                        <span>SMS Credits:</span>
                                        <span id="summarySmsCredits">100</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between py-2 border-bottom">
                                        <span>Cost Per Credit:</span>
                                        <span>{{ $user->currency->symbol }}{{ number_format($smsRate, 3) }}</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between py-2 fw-bold">
                                        <span>Total Cost:</span>
                                        <span id="summaryTotalCost">{{ $user->currency->symbol }}{{ number_format(100 * $smsRate, 2) }}</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between py-2 border-top fw-bold">
                                        <span>Remaining Balance:</span>
                                        <span id="summaryRemainingBalance" class="text-success">{{ $user->currency->symbol }}{{ number_format($wallet->balance - (100 * $smsRate), 2) }}</span>
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
                                    <h2 class="fw-bold">SMS Credits Usage</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <p class="fs-6 text-gray-700 mb-4">SMS credits are used for:</p>
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-primary me-2"></span>
                                        <span>Sending bulk SMS messages</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-primary me-2"></span>
                                        <span>Automated notifications</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-primary me-2"></span>
                                        <span>SMS campaigns</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="bullet bullet-dot bg-primary me-2"></span>
                                        <span>Two-factor authentication</span>
                                    </li>
                                </ul>
                                
                                <div class="separator separator-dashed my-5"></div>
                                
                                <p class="text-gray-700 mb-0">Need more information? <a href="#">View SMS pricing</a></p>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const creditsInput = document.getElementById('credits');
        const totalCostSpan = document.getElementById('totalCost');
        const summarySmsCredits = document.getElementById('summarySmsCredits');
        const summaryTotalCost = document.getElementById('summaryTotalCost');
        const summaryRemainingBalance = document.getElementById('summaryRemainingBalance');
        const smsRate = {{ $smsRate }};
        const walletBalance = {{ $wallet->balance }};
        const currencySymbol = '{{ $user->currency->symbol }}';
        
        function updateTotalCost() {
            const credits = parseInt(creditsInput.value) || 0;
            const totalCost = credits * smsRate;
            
            // Format with 3 decimal places for single credit to show exact rate (0.035)
            // Otherwise use 2 decimal places for larger amounts
            let formattedTotalCost;
            if (credits === 1) {
                formattedTotalCost = new Intl.NumberFormat().format(totalCost.toFixed(3));
            } else {
                formattedTotalCost = new Intl.NumberFormat().format(totalCost.toFixed(2));
            }
            
            const remainingBalance = walletBalance - totalCost;
            const formattedRemainingBalance = new Intl.NumberFormat().format(remainingBalance.toFixed(2));
            
            totalCostSpan.textContent = formattedTotalCost;
            summarySmsCredits.textContent = credits;
            summaryTotalCost.textContent = currencySymbol + formattedTotalCost;
            summaryRemainingBalance.textContent = currencySymbol + formattedRemainingBalance;
            
            // Change text color if balance would go negative
            if (remainingBalance < 0) {
                summaryRemainingBalance.classList.remove('text-success');
                summaryRemainingBalance.classList.add('text-danger');
            } else {
                summaryRemainingBalance.classList.remove('text-danger');
                summaryRemainingBalance.classList.add('text-success');
            }
        }
        
        creditsInput.addEventListener('input', updateTotalCost);
        updateTotalCost(); // Initialize on page load
    });
</script>
@endpush
