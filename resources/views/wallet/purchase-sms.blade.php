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
                                        <i class="ki-outline ki-message-text-2 fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Purchase SMS Credits</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Wallet Conversion</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Convert wallet funds into high-speed SMS broadcasting credits at wholesale rates.</p>
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
                    <!-- Purchase Form Column -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Credit Package Conversion</span>
                                    <span class="text-gray-400 mt-1 fs-7">Enter credits count to deduct from your wallet balance</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form action="{{ route('wallet.process-purchase-sms') }}" method="POST" id="purchaseSmsForm">
                                    @csrf
                                    
                                    <!-- Wallet Balance Info Banner -->
                                    <div class="alert alert-primary border border-primary/20 d-flex align-items-center p-4 rounded-3 mb-6 shadow-xs">
                                        <i class="ki-outline ki-wallet fs-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0.5 font-bold text-primary fs-6">Available Wallet Funds</h6>
                                            <span class="fs-7 text-gray-700 font-medium">
                                                Balance: <strong class="text-gray-900 font-mono">{{ $user->currency->symbol }}{{ number_format($wallet->balance, 2) }}</strong> — Max credits possible: <strong class="text-primary font-mono">{{ number_format($estimatedCredits) }}</strong> (Rate: {{ $user->currency->symbol }}{{ number_format($smsRate, 3) }}/sms)
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Credits Input -->
                                    <div class="mb-6">
                                        <label for="credits" class="form-label font-bold text-gray-800 fs-6 mb-2">Number of SMS Credits</label>
                                        <div class="input-group input-group-solid rounded-3">
                                            <input type="number" 
                                                class="form-control form-control-solid font-mono font-bold text-gray-900 fs-5 @error('credits') is-invalid @enderror" 
                                                id="credits" 
                                                name="credits" 
                                                min="1" 
                                                max="{{ $estimatedCredits }}"
                                                value="{{ old('credits', 100) }}"
                                                required>
                                            <span class="input-group-text font-bold text-gray-700">Credits</span>
                                        </div>
                                        @error('credits')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div id="costCalculation" class="text-gray-600 font-semibold fs-7 mt-2">
                                            Total Calculated Cost: <strong class="text-gray-900 font-mono">{{ $user->currency->symbol }}<span id="totalCost">{{ number_format(100 * $smsRate, 2) }}</span></strong>
                                        </div>
                                    </div>
                                    
                                    <!-- Notice Box -->
                                    <div class="alert alert-warning border border-warning/20 d-flex align-items-center p-4 rounded-3 mb-6 shadow-xs">
                                        <i class="ki-outline ki-information-5 fs-2x text-warning me-3"></i>
                                        <div>
                                            <h6 class="mb-0.5 font-bold text-warning fs-7">Non-Refundable Conversion</h6>
                                            <span class="fs-8 text-gray-700 font-medium">Purchasing SMS credits will deduct funds from your wallet. Purchased SMS credits are non-refundable.</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-3 mt-6">
                                        <a href="{{ route('wallet.index') }}" class="btn btn-light font-bold px-5">Cancel</a>
                                        <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                            <i class="ki-outline ki-check me-2 fs-4"></i>Purchase SMS Credits Now
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Live Purchase Summary Sidebar Column -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-top: 4px solid #10b981 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Conversion Summary</span>
                                    <span class="text-gray-400 mt-1 fs-8">Live debit & balance calculation</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2">
                                <div class="d-flex flex-column gap-3 fs-7">
                                    <div class="d-flex justify-content-between p-3 bg-light-soft rounded-3">
                                        <span class="text-gray-600 font-medium">Current Balance:</span>
                                        <span class="font-mono text-gray-900 font-bold">{{ $user->currency->symbol }}{{ number_format($wallet->balance, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between p-3 bg-light-soft rounded-3">
                                        <span class="text-gray-600 font-medium">SMS Credits:</span>
                                        <span id="summarySmsCredits" class="font-mono text-primary font-bold">100</span>
                                    </div>
                                    <div class="d-flex justify-content-between p-3 bg-light-soft rounded-3">
                                        <span class="text-gray-600 font-medium">Rate / Credit:</span>
                                        <span class="font-mono text-gray-800 font-semibold">{{ $user->currency->symbol }}{{ number_format($smsRate, 3) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between p-3 bg-light-soft rounded-3">
                                        <span class="text-gray-600 font-bold">Total Cost:</span>
                                        <span id="summaryTotalCost" class="font-mono text-gray-900 font-bold">{{ $user->currency->symbol }}{{ number_format(100 * $smsRate, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between p-3 bg-light-success rounded-3 border border-success/20">
                                        <span class="text-success font-bold">Remaining Balance:</span>
                                        <span id="summaryRemainingBalance" class="font-mono text-success font-extrabold fs-6">{{ $user->currency->symbol }}{{ number_format($wallet->balance - (100 * $smsRate), 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm overflow-hidden bg-light-soft" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0 bg-transparent">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-6">SMS Credit Usage</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 fs-7 text-gray-700">
                                <ul class="ps-4 mb-0">
                                    <li class="mb-2">Bulk SMS broadcasting campaigns</li>
                                    <li class="mb-2">Automated customer notifications</li>
                                    <li class="mb-2">Two-Factor Authentication (OTP)</li>
                                    <li class="mb-2">Transactional SMS webhooks</li>
                                </ul>
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
            
            let formattedTotalCost;
            if (credits === 1) {
                formattedTotalCost = new Intl.NumberFormat().format(totalCost.toFixed(3));
            } else {
                formattedTotalCost = new Intl.NumberFormat().format(totalCost.toFixed(2));
            }
            
            const remainingBalance = walletBalance - totalCost;
            const formattedRemainingBalance = new Intl.NumberFormat().format(remainingBalance.toFixed(2));
            
            if (totalCostSpan) totalCostSpan.textContent = formattedTotalCost;
            if (summarySmsCredits) summarySmsCredits.textContent = credits;
            if (summaryTotalCost) summaryTotalCost.textContent = currencySymbol + formattedTotalCost;
            if (summaryRemainingBalance) {
                summaryRemainingBalance.textContent = currencySymbol + formattedRemainingBalance;
                
                if (remainingBalance < 0) {
                    summaryRemainingBalance.classList.remove('text-success');
                    summaryRemainingBalance.classList.add('text-danger');
                } else {
                    summaryRemainingBalance.classList.remove('text-danger');
                    summaryRemainingBalance.classList.add('text-success');
                }
            }
        }
        
        if (creditsInput) {
            creditsInput.addEventListener('input', updateTotalCost);
            updateTotalCost();
        }
    });
</script>
@endpush
