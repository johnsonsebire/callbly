@extends('layouts.master')

@section('title', $title ?? 'Coming Soon')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        
                        <!-- Hero Card -->
                        <div class="card border-0 shadow-sm overflow-hidden mb-6" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-body text-center py-10 px-6 sm:px-10">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mx-auto mb-5 p-3">
                                    <i class="ki-outline ki-code fs-3hx text-primary"></i>
                                </div>
                                
                                <h1 class="font-extrabold fs-2x text-gray-900 mb-3">{{ $title ?? 'Feature Under Development' }}</h1>
                                
                                <p class="text-gray-500 fs-6 max-w-500px mx-auto mb-6">{{ $description ?? 'We are actively building this module to provide you with seamless communication capabilities.' }}</p>
                                
                                <div class="alert alert-primary border border-primary/20 d-flex align-items-center justify-content-center p-4 rounded-3 mb-6 max-w-600px mx-auto shadow-xs">
                                    <i class="ki-outline ki-shield-tick fs-2x text-primary me-3"></i>
                                    <div class="text-start">
                                        <h6 class="mb-0.5 font-bold text-primary fs-6">Feature Launching Soon!</h6>
                                        <span class="fs-8 text-gray-700 font-medium">Our engineering team is finalizing testing. This service will be available shortly.</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-center flex-wrap gap-3">
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                        <i class="ki-outline ki-arrow-left fs-4 me-2"></i>Back to Dashboard
                                    </a>
                                    <a href="{{ route('sms.dashboard') }}" class="btn btn-light-primary font-bold px-6 py-3">
                                        <i class="ki-outline ki-message-text-2 fs-4 me-2"></i>Try SMS Services
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 3 Feature Highlight Cards -->
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100 p-5 text-center" style="border-radius: 1.25rem;">
                                    <div class="symbol symbol-45px symbol-circle bg-light-success mx-auto mb-3 p-2">
                                        <i class="ki-outline ki-notification-bing fs-2x text-success"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 fs-6 mb-1">Get Notified</h4>
                                    <p class="text-gray-500 fs-8 mb-0">We will notify you via email as soon as this feature goes live.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100 p-5 text-center" style="border-radius: 1.25rem;">
                                    <div class="symbol symbol-45px symbol-circle bg-light-warning mx-auto mb-3 p-2">
                                        <i class="ki-outline ki-message-question fs-2x text-warning"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 fs-6 mb-1">Have Questions?</h4>
                                    <p class="text-gray-500 fs-8 mb-0">Reach out to our support team for product roadmap details.</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100 p-5 text-center" style="border-radius: 1.25rem;">
                                    <div class="symbol symbol-45px symbol-circle bg-light-info mx-auto mb-3 p-2">
                                        <i class="ki-outline ki-rocket fs-2x text-info"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 fs-6 mb-1">Early Access</h4>
                                    <p class="text-gray-500 fs-8 mb-0">Join our beta tester queue to get early access to new releases.</p>
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
