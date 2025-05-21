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
                                        <i class="ki-outline ki-ranking fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">SMS Billing Tiers</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-tags fs-4 me-1"></i>
                                                Your Current Tier: <strong class="ms-1">{{ $currentTier->name }}</strong>
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-dollar fs-4 me-1"></i>
                                                Rate: <strong class="ms-1">{{ $formattedSmsRate }} per SMS</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('sms.credits') }}" class="btn btn-sm btn-primary">
                                            <i class="ki-outline ki-plus-square fs-3"></i>
                                            Buy SMS Credits
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

                <!-- Billing Tiers Table -->
                <div class="card card-flush h-lg-100 mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Available SMS Billing Tiers</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">As you purchase more SMS credits, you'll automatically be upgraded to higher tiers with better rates</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-100px">Tier</th>
                                        <th class="min-w-150px">Price per SMS (GHS)</th>
                                        <th class="min-w-150px">Price per SMS ({{ $userCurrency->code }})</th>
                                        <th class="min-w-150px">Minimum Purchase</th>
                                        <th class="min-w-150px">Maximum Purchase</th>
                                        <th class="min-w-200px">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allTiers as $tier)
                                    <tr class="{{ $tier->id === $currentTier->id ? 'bg-light-primary' : '' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-3">
                                                    <span class="symbol-label bg-light-{{ $tier->id === $currentTier->id ? 'primary' : 'dark' }}">
                                                        <i class="ki-outline ki-award fs-2 text-{{ $tier->id === $currentTier->id ? 'primary' : 'dark' }}"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <span class="text-dark fw-bold text-hover-primary fs-6">{{ $tier->name }}</span>
                                                    @if($tier->id === $currentTier->id)
                                                        <span class="text-primary fw-semibold d-block fs-7">Your current tier</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bold fs-6">₵{{ number_format($tier->price_per_sms, 3) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bold fs-6">{{ $userCurrency->symbol }}{{ number_format($tier->price_per_sms * $userCurrency->exchange_rate, 3) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold d-block fs-6">₵{{ number_format($tier->min_purchase, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold d-block fs-6">
                                                @if($tier->max_purchase)
                                                    ₵{{ number_format($tier->max_purchase, 2) }}
                                                @else
                                                    Unlimited
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-semibold d-block fs-7">{{ $tier->description }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- How Tiers Work -->
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">How Billing Tiers Work</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Understanding our tiered pricing system</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <div class="card card-flush h-lg-100">
                                    <div class="card-header pt-5">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Tier Qualification</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-primary">
                                                        <i class="ki-outline ki-flag fs-3 text-primary"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">All users start on the Basic tier</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-success">
                                                        <i class="ki-outline ki-arrow-up-right fs-3 text-success"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Purchasing ₵1,500 - ₵2,999 worth of SMS qualifies you for the Plus tier</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-info">
                                                        <i class="ki-outline ki-arrow-up-right fs-3 text-info"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Purchasing ₵3,000 - ₵5,999 worth of SMS qualifies you for the Premium tier</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-warning">
                                                        <i class="ki-outline ki-medal fs-3 text-warning"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Purchases of ₵6,000 or more qualify you for the Gold tier</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-flush h-lg-100">
                                    <div class="card-header pt-5">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Tier Benefits</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-success">
                                                        <i class="ki-outline ki-dollar fs-3 text-success"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Higher tiers offer lower per-SMS rates</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-info">
                                                        <i class="ki-outline ki-chart-simple fs-3 text-info"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Tier upgrades happen automatically based on your purchase volume</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-primary">
                                                        <i class="ki-outline ki-check-square fs-3 text-primary"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Once upgraded, you maintain your tier level for future purchases</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-30px me-3">
                                                    <span class="symbol-label bg-light-warning">
                                                        <i class="ki-outline ki-shield-tick fs-3 text-warning"></i>
                                                    </span>
                                                </div>
                                                <span class="text-gray-800">Tier pricing applies to all your SMS sending</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning d-flex align-items-center p-5 mt-6">
                            <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-warning">Important Note</h4>
                                <span>When making purchases on higher tiers, there are minimum purchase requirements:</span>
                                <ul class="my-3">
                                    <li>Plus tier: Minimum purchase of ₵1,500</li>
                                    <li>Premium tier: Minimum purchase of ₵3,000</li>
                                    <li>Gold tier: Minimum purchase of ₵6,000</li>
                                </ul>
                            </div>
                        </div>
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