@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xl-8">
                        <!--begin::Card-->
                        <div class="card card-flush mb-5">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Currency Settings</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if(session('success'))
                                    <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                        <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-success">Success</h4>
                                            <span>{{ session('success') }}</span>
                                        </div>
                                        <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                            <i class="ki-outline ki-cross fs-2 text-success"></i>
                                        </button>
                                    </div>
                                @endif

                                <div class="mb-5">
                                    <p class="fs-6 fw-semibold text-gray-600">Select your preferred currency for displaying prices and billing information.</p>
                                </div>
                                
                                <div class="separator my-10"></div>
                                
                                <form method="POST" action="{{ route('settings.currency.update') }}">
                                    @csrf
                                    
                                    <div class="fv-row mb-7">
                                        <label for="currency_id" class="required fw-semibold fs-6 mb-2">Currency</label>
                                        <select name="currency_id" id="currency_id" class="form-select form-select-solid @error('currency_id') is-invalid @enderror" data-control="select2" data-placeholder="Select currency">
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency->id }}" 
                                                    {{ $currentCurrency->id === $currency->id ? 'selected' : '' }}>
                                                    {{ $currency->name }} ({{ $currency->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('currency_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="separator separator-dashed my-8"></div>
                                    
                                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mb-10">
                                        <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                                        <div class="d-flex flex-stack flex-grow-1">
                                            <div class="fw-semibold">
                                                <h4 class="text-gray-900 fw-bold">Note</h4>
                                                <div class="fs-6 text-gray-700">Changing your currency doesn't affect the actual amount in your account. It only changes how prices are displayed.</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="indicator-label">Save Currency Preference</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->

                        <!--begin::Card-->
                        <div class="card card-flush mb-xl-10">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Current Exchange Rates</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <p class="fs-6 fw-semibold text-gray-600 mb-5">For reference, here are the current exchange rates relative to the Ghanaian Cedi (GHS):</p>
                                
                                <!--begin::Table-->
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th class="min-w-125px">Currency</th>
                                                <th class="min-w-125px">Code</th>
                                                <th class="min-w-125px">Symbol</th>
                                                <th class="min-w-125px">Exchange Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($currencies as $currency)
                                                <tr>
                                                    <td>{{ $currency->name }}</td>
                                                    <td><span class="badge badge-light-primary fw-bold">{{ $currency->code }}</span></td>
                                                    <td>{{ $currency->symbol }}</td>
                                                    <td>
                                                        @if($currency->code === 'GHS')
                                                            <span class="fw-bold text-dark">1.0000</span> <span class="text-muted">(Base Currency)</span>
                                                        @else
                                                            <span class="fw-bold text-dark">{{ number_format($currency->exchange_rate, 4) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    
                    <div class="col-xl-4">
                        <!--begin::Card-->
                        <div class="card card-flush mb-xl-10">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bold">Current Settings</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-5">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-dollar fs-3 text-primary"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Current Currency</a>
                                                <span class="text-muted fw-bold">{{ $currentCurrency->name }} ({{ $currentCurrency->code }})</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    <div class="separator separator-dashed my-5"></div>
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-5">
                                                <span class="symbol-label bg-light-success">
                                                    <i class="ki-outline ki-abstract-41 fs-3 text-success"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Symbol</a>
                                                <span class="text-muted fw-bold">{{ $currentCurrency->symbol }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    <div class="separator separator-dashed my-5"></div>
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-5">
                                                <span class="symbol-label bg-light-warning">
                                                    <i class="ki-outline ki-chart-line-down fs-3 text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Exchange Rate</a>
                                                <span class="text-muted fw-bold">
                                                    @if($currentCurrency->code === 'GHS')
                                                        1.0000 (Base)
                                                    @else
                                                        {{ number_format($currentCurrency->exchange_rate, 4) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                </div>

                                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mt-10">
                                    <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <div class="fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">Currency Effects</h4>
                                            <div class="fs-6 text-gray-700">Your currency preference affects how prices are displayed throughout the application. All transactions and account balances are maintained in GHS.</div>
                                        </div>
                                    </div>
                                </div>
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