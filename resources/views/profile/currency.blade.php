@extends('layouts.master')

@section('title', 'Currency Settings')

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
                                        <i class="ki-outline ki-dollar fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Currency Settings</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Multi-Currency</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Select your preferred currency display preference and review current global exchange rates.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-shield-tick fs-2x text-success me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="mb-0 text-success font-bold fs-6">Success</h5>
                            <span class="fs-7 text-gray-700">{{ session('success') }}</span>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-success rounded-circle" data-bs-dismiss="alert">
                            <i class="ki-outline ki-cross fs-3 text-success"></i>
                        </button>
                    </div>
                @endif

                <div class="row g-5 g-xl-8">
                    <!-- Main Form & Table Column -->
                    <div class="col-xl-8">
                        <!-- Preference Selector Card -->
                        <div class="card border-0 shadow-sm overflow-hidden mb-6" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Select Active Currency</span>
                                    <span class="text-gray-400 mt-1 fs-7">Choose how pricing figures are presented throughout Callbly</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form method="POST" action="{{ route('settings.currency.update') }}">
                                    @csrf
                                    
                                    <div class="mb-6">
                                        <label for="currency_id" class="required form-label font-bold text-gray-800 fs-7 mb-2">Display Currency</label>
                                        <select name="currency_id" id="currency_id" class="form-select form-select-solid rounded-3 fs-6 @error('currency_id') is-invalid @enderror" data-control="select2" data-placeholder="Select currency">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}" 
                                                    {{ $currentCurrency->id === $currency->id ? 'selected' : '' }}>
                                                    {{ $currency->name }} ({{ $currency->code }} - {{ $currency->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('currency_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="alert alert-primary border border-primary/20 d-flex align-items-center p-4 rounded-3 mb-6 shadow-xs">
                                        <i class="ki-outline ki-information-5 fs-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0.5 font-bold text-primary fs-7">Display Conversion Info</h6>
                                            <span class="fs-8 text-gray-700 font-medium">Changing display currency converts displayed balances dynamically. All core transactions remain anchored to GHS.</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                            <i class="ki-outline ki-check me-1 fs-4"></i> Save Currency Preference
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Exchange Rates Table Card -->
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #10b981 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Current Global Exchange Rates</span>
                                    <span class="text-gray-400 mt-1 fs-7">Live conversion multipliers relative to Ghanaian Cedi (GHS)</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                                <th class="min-w-150px">Currency</th>
                                                <th class="min-w-100px">Code</th>
                                                <th class="min-w-100px">Symbol</th>
                                                <th class="min-w-150px text-end">Exchange Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($currencies as $currency)
                                                <tr>
                                                    <td>
                                                        <span class="font-bold text-gray-900 fs-7">{{ $currency->name }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-light-primary font-bold fs-8 px-2.5 py-1">{{ $currency->code }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="font-mono font-bold text-gray-800 fs-6">{{ $currency->symbol }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        @if($currency->code === 'GHS')
                                                            <span class="font-mono text-gray-900 font-extrabold fs-7">1.0000</span>
                                                            <span class="text-gray-400 fs-8 font-medium ms-1">(Base)</span>
                                                        @else
                                                            <span class="font-mono text-gray-900 font-bold fs-7">{{ number_format($currency->exchange_rate, 4) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Settings Sidebar Column -->
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Active Preference</span>
                                    <span class="text-gray-400 mt-1 fs-8">Current active account state</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-center p-3 bg-light-primary rounded-3 border border-primary/20">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-primary rounded-circle">
                                                <i class="ki-outline ki-dollar fs-3 text-white"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-500 font-bold fs-8 uppercase">Active Currency</span>
                                            <span class="text-gray-900 font-extrabold fs-6">{{ $currentCurrency->name }} ({{ $currentCurrency->code }})</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center p-3 bg-light-soft rounded-3 border border-gray-200">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-success text-success rounded-circle">
                                                <i class="ki-outline ki-abstract-41 fs-3"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-500 font-bold fs-8 uppercase">Currency Symbol</span>
                                            <span class="font-mono text-gray-900 font-extrabold fs-6">{{ $currentCurrency->symbol }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center p-3 bg-light-soft rounded-3 border border-gray-200">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-warning text-warning rounded-circle">
                                                <i class="ki-outline ki-chart-line-down fs-3"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-500 font-bold fs-8 uppercase">Conversion Factor</span>
                                            <span class="font-mono text-gray-900 font-extrabold fs-6">
                                                @if($currentCurrency->code === 'GHS')
                                                    1.0000 (Base GHS)
                                                @else
                                                    {{ number_format($currentCurrency->exchange_rate, 4) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection