@extends('layouts.master')

@section('title', 'Brand Sender ID Registry')

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
                                        <i class="ki-outline ki-shield-search fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Brand Sender ID Registry</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Identity & Compliance</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Register and manage custom alphanumeric Sender IDs for brand identity and regulatory compliance.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.compose') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-paper-plane me-1 fs-5"></i> Compose SMS
                                </a>
                                <a href="{{ route('sms.dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> SMS Overview
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                        <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                        <span class="font-bold text-gray-800 fs-6">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="row g-5 g-xl-8">
                    <!-- Sender Names List Column -->
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Registered Sender IDs</span>
                                    <span class="text-gray-400 mt-1 fs-7">Approved brand headers available for SMS dispatch</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                @if($senderNames->isEmpty())
                                    <div class="text-center py-12">
                                        <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                            <i class="ki-outline ki-shield-search fs-3hx text-primary"></i>
                                        </div>
                                        <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Sender IDs Registered</h3>
                                        <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-4">You haven't registered any custom Sender IDs yet. Register your company or brand name to personalize outgoing SMS broadcasts.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                                    <th class="min-w-160px">Sender ID</th>
                                                    <th class="min-w-100px">Status</th>
                                                    <th class="min-w-130px">Date Registered</th>
                                                    <th class="min-w-200px">Approval Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($senderNames as $senderName)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="symbol symbol-40px me-3">
                                                                    <span class="symbol-label bg-light-primary rounded-circle">
                                                                        <i class="ki-outline ki-shield-tick fs-3 text-primary"></i>
                                                                    </span>
                                                                </div>
                                                                <span class="font-extrabold text-gray-900 fs-6 tracking-wide">{{ $senderName->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($senderName->status == 'approved')
                                                                <span class="badge badge-light-success fs-8 font-bold px-2.5 py-1 rounded-pill">Approved</span>
                                                            @elseif($senderName->status == 'rejected')
                                                                <span class="badge badge-light-danger fs-8 font-bold px-2.5 py-1 rounded-pill">Rejected</span>
                                                            @else
                                                                <span class="badge badge-light-warning fs-8 font-bold px-2.5 py-1 rounded-pill">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-800 font-semibold fs-7">{{ $senderName->created_at->format('M d, Y') }}</span>
                                                        </td>
                                                        <td>
                                                            @if($senderName->status == 'pending')
                                                                <span class="text-warning font-semibold fs-7 d-flex align-items-center">
                                                                    <i class="ki-outline ki-timer fs-5 me-1 text-warning"></i> Awaiting telecom approval
                                                                </span>
                                                            @elseif($senderName->status == 'rejected')
                                                                <span class="text-danger font-semibold fs-7 d-flex align-items-center">
                                                                    <i class="ki-outline ki-cross-circle fs-5 me-1 text-danger"></i> {{ $senderName->rejection_reason ?? 'Rejected by provider' }}
                                                                </span>
                                                            @else
                                                                <span class="text-success font-semibold fs-7 d-flex align-items-center">
                                                                    <i class="ki-outline ki-check-circle fs-5 me-1 text-success"></i> Active & ready to use
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Register & Guidelines Column -->
                    <div class="col-xl-4">
                        <!-- Register New Sender ID Card -->
                        <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Register New Sender ID</span>
                                    <span class="text-gray-400 mt-1 fs-7">Submit custom Sender ID for review</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form method="POST" action="{{ route('sms.sender-names.store') }}">
                                    @csrf
                                    <div class="mb-5">
                                        <label for="name" class="form-label font-bold text-gray-800 fs-6 mb-2">Sender Name <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <input type="text" name="name" id="name" class="form-control form-control-solid ps-12 @error('name') is-invalid @enderror" 
                                                value="{{ old('name') }}" placeholder="e.g. CALLBLY" maxlength="11" required>
                                            <i class="ki-outline ki-shield-search position-absolute top-50 translate-middle-y ms-4 fs-3 text-gray-400"></i>
                                        </div>
                                        <div class="form-text text-gray-400 fs-7 mt-1.5">
                                            Must be 3-11 alphanumeric characters (A-Z, 0-9). No spaces.
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block mt-1 font-semibold">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary font-bold w-100 py-3 shadow-sm">
                                        <i class="ki-outline ki-plus-circle fs-4 me-2"></i>Submit Sender ID
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Guidelines Card -->
                        <div class="card border-0 shadow-sm overflow-hidden bg-light-soft" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0 bg-transparent">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Registration Requirements</span>
                                    <span class="text-gray-400 mt-1 fs-8">Telecom regulation criteria</span>
                                </h3>
                                <div class="card-toolbar">
                                    <i class="ki-outline ki-information-5 fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div class="card-body pt-2 fs-7">
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-primary me-2.5 h-8px w-8px"></span>
                                        <span class="text-gray-700 font-medium">3 to 11 characters maximum</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-primary me-2.5 h-8px w-8px"></span>
                                        <span class="text-gray-700 font-medium">Alphanumeric only (A-Z, 0-9)</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-danger me-2.5 h-8px w-8px"></span>
                                        <span class="text-gray-700 font-medium">No spaces, symbols, or punctuation</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <span class="bullet bullet-dot bg-info me-2.5 h-8px w-8px"></span>
                                        <span class="text-gray-700 font-medium">Approval completes within 1-3 business days</span>
                                    </li>
                                    <li class="d-flex align-items-center">
                                        <span class="bullet bullet-dot bg-warning me-2.5 h-8px w-8px"></span>
                                        <span class="text-gray-700 font-medium">Generic names (e.g. "INFO", "ALERT") may be rejected</span>
                                    </li>
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