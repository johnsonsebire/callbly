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
                                        <i class="ki-outline ki-call fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <h2 class="text-gray-900 fs-2 fw-bold me-1">Create USSD Service</h2>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-calendar fs-4 me-1"></i>
                                                {{ now()->format('F d, Y') }}
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-compass fs-4 me-1"></i>
                                                Set up a new USSD service for your business
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('ussd.services') }}" class="btn btn-sm btn-light-primary">
                                            <i class="ki-outline ki-element-11 fs-3"></i>
                                            Back to Services
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create USSD Service Form -->
                <div class="card card-flush h-lg-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Service Details</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Enter the details for your new USSD service</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        <form action="{{ route('ussd.store') }}" method="POST">
                            @csrf
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Service Name</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                        placeholder="Enter service name" name="name" value="{{ old('name') }}" required />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Shortcode</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-solid @error('shortcode') is-invalid @enderror" 
                                        placeholder="e.g., *123#" name="shortcode" value="{{ old('shortcode') }}" required />
                                    <div class="form-text">The shortcode users will dial to access your service</div>
                                    @error('shortcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Menu Structure</label>
                                <div class="col-lg-8">
                                    <textarea class="form-control form-control-solid @error('menu_structure') is-invalid @enderror" 
                                        rows="10" name="menu_structure" required>{{ old('menu_structure') }}</textarea>
                                    <div class="form-text">Enter your menu structure in JSON format. See documentation for structure guidelines.</div>
                                    @error('menu_structure')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Callback URL (Optional)</label>
                                <div class="col-lg-8">
                                    <input type="url" class="form-control form-control-solid @error('callback_url') is-invalid @enderror" 
                                        placeholder="https://your-website.com/callback" name="callback_url" value="{{ old('callback_url') }}" />
                                    <div class="form-text">URL to receive session updates and user inputs</div>
                                    @error('callback_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('ussd.services') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">Create Service</span>
                                </button>
                            </div>
                        </form>
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