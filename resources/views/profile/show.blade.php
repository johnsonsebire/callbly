@extends('layouts.master')

@section('title', 'User Profile')

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
                                <div class="symbol symbol-65px symbol-lg-80px symbol-circle border border-2 border-cyan-400/40 p-1 shadow-inner" style="background: rgba(14, 165, 233, 0.15);">
                                    <div class="symbol-label font-extrabold text-white fs-1 rounded-circle bg-primary">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">{{ $user->name }}</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">
                                            {{ $user->email_verified_at ? 'Verified Account' : 'Standard User' }}
                                        </span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">{{ $user->email }} • {{ $user->company_name ?? 'Personal Account' }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-pencil me-1 fs-5"></i> Edit Profile
                                </a>
                                <a href="{{ route('settings.currency') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-dollar me-1 fs-5"></i> Currency Settings
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
                    <!-- Profile Information Column -->
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Account Details</span>
                                    <span class="text-gray-400 mt-1 fs-7">Your verified account information and contact credentials</span>
                                </h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-light-primary btn-sm font-bold">
                                        <i class="ki-outline ki-pencil me-1 fs-5"></i> Edit
                                    </a>
                                </div>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="p-4 bg-light-soft rounded-3 border border-gray-200">
                                            <span class="text-gray-500 font-bold fs-8 uppercase d-block mb-1">Full Name</span>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-user fs-4 text-primary me-2"></i>
                                                <span class="font-bold text-gray-900 fs-6">{{ $user->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 bg-light-soft rounded-3 border border-gray-200">
                                            <span class="text-gray-500 font-bold fs-8 uppercase d-block mb-1">Email Address</span>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-sms fs-4 text-primary me-2"></i>
                                                <span class="font-bold text-gray-900 fs-6">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 bg-light-soft rounded-3 border border-gray-200">
                                            <span class="text-gray-500 font-bold fs-8 uppercase d-block mb-1">Phone Number</span>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-phone fs-4 text-primary me-2"></i>
                                                <span class="font-bold text-gray-900 fs-6">{{ $user->phone ?? 'Not set' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-4 bg-light-soft rounded-3 border border-gray-200">
                                            <span class="text-gray-500 font-bold fs-8 uppercase d-block mb-1">Company / Organization</span>
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-briefcase fs-4 text-primary me-2"></i>
                                                <span class="font-bold text-gray-900 fs-6">{{ $user->company_name ?? 'Not set' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Status Column -->
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #10b981 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Account Status</span>
                                    <span class="text-gray-400 mt-1 fs-7">Verification & security status</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-center p-3 bg-light-success rounded-3 border border-success/20">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-success text-white rounded-circle">
                                                <i class="ki-outline ki-shield-tick fs-3"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-900 font-bold fs-7">Email Verification</span>
                                            <span class="badge badge-light-success font-bold fs-8 w-fit mt-0.5">
                                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center p-3 bg-light-soft rounded-3 border border-gray-200">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                <i class="ki-outline ki-calendar fs-3"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-500 font-semibold fs-8 uppercase">Member Since</span>
                                            <span class="text-gray-900 font-bold fs-7">{{ $user->created_at->format('M d, Y') }}</span>
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