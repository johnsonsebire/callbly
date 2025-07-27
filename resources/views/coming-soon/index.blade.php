@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body text-center py-20">
                                <div class="mb-10">
                                    <i class="{{ $icon }} fs-5x text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </div>
                                
                                <h1 class="fw-bolder fs-2qx text-gray-900 mb-7">{{ $title }}</h1>
                                
                                <div class="fw-semibold fs-6 text-gray-500 mb-7">{{ $description }}</div>
                                
                                <div class="mb-11">
                                    <div class="alert alert-primary d-flex align-items-center p-5">
                                        <i class="ki-duotone ki-shield-tick fs-2hx text-primary me-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-primary">Coming Soon!</h4>
                                            <span>We're working hard to bring you this feature. It will be available soon.</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-0">
                                    <a href="{{ route('dashboard') }}" class="btn btn-lg btn-primary me-3">
                                        <i class="ki-duotone ki-arrow-left fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Back to Dashboard
                                    </a>
                                    <a href="{{ route('sms.dashboard') }}" class="btn btn-lg btn-light-primary">
                                        <i class="ki-duotone ki-message-text-2 fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        Try SMS Services
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-10">
                            <div class="col-lg-4">
                                <div class="card card-bordered h-100">
                                    <div class="card-body text-center">
                                        <i class="ki-duotone ki-notification-bing fs-3x text-success mb-5">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <h4 class="fw-bold text-gray-900 mb-3">Get Notified</h4>
                                        <p class="text-muted">We'll notify you via email when this feature becomes available.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-bordered h-100">
                                    <div class="card-body text-center">
                                        <i class="ki-duotone ki-message-question fs-3x text-warning mb-5">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <h4 class="fw-bold text-gray-900 mb-3">Have Questions?</h4>
                                        <p class="text-muted">Contact our support team for more information about upcoming features.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-bordered h-100">
                                    <div class="card-body text-center">
                                        <i class="ki-duotone ki-rocket fs-3x text-info mb-5">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <h4 class="fw-bold text-gray-900 mb-3">Early Access</h4>
                                        <p class="text-muted">Be among the first to try new features when they're released.</p>
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

@section('styles')
<style>
.card-bordered {
    border: 1px solid var(--bs-gray-300);
}
</style>
@endsection
