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
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card card-flush h-100">
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-call fs-2x text-primary"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="fs-2 text-gray-800 mb-0">{{ $activeNumbers }}</h3>
                                        <span class="text-gray-500 fw-semibold">Active Numbers</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card card-flush h-100">
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="symbol symbol-40px me-3">
                                        <span class="symbol-label bg-light-warning">
                                            <i class="ki-outline ki-timer fs-2x text-warning"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="fs-2 text-gray-800 mb-0">{{ $expiringNumbers }}</h3>
                                        <span class="text-gray-500 fw-semibold">Expiring Soon</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Virtual Numbers</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if($activeNumbers == 0)
                                    <div class="text-center py-5">
                                        <img src="{{ asset('assets/media/svg/illustrations/calling.svg') }}" class="mw-400px mb-5" alt="">
                                        <h3 class="fs-2 mb-3">Get Started with Virtual Numbers</h3>
                                        <p class="text-gray-500 mb-4">Browse our selection of local and toll-free numbers<br>to establish your presence in multiple regions.</p>
                                        <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary">Browse Numbers</a>
                                    </div>
                                @endif
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>

                @if($activeNumbers > 0)
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Your Numbers</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary">Get More Numbers</a>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th>Number</th>
                                                <th>Type</th>
                                                <th>Monthly Fee</th>
                                                <th>Status</th>
                                                <th>Expires</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-6 fw-semibold text-gray-600">
                                            @foreach($virtualNumbers as $number)
                                            <tr>
                                                <td>{{ $number->number }}</td>
                                                <td>{{ ucfirst($number->type) }}</td>
                                                <td>{{ $number->monthly_fee }}</td>
                                                <td>
                                                    <span class="badge badge-light-{{ $number->status === 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($number->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $number->expires_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-light btn-active-light-primary">
                                                        Manage
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
                @endif
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>
@endsection