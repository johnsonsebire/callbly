@extends('layouts.master')

@section('title', 'Edit Profile')

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
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Edit Profile</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Account Settings</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Update your personal contact credentials and organization details.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> View Profile
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
                    <!-- Edit Form Column -->
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Personal & Business Details</span>
                                    <span class="text-gray-400 mt-1 fs-7">Update your account information below</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-5">
                                        <label for="name" class="required form-label font-bold text-gray-800 fs-7 mb-2">Full Name</label>
                                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                            class="form-control form-control-solid rounded-3 @error('name') is-invalid @enderror">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label for="email" class="required form-label font-bold text-gray-800 fs-7 mb-2">Email Address</label>
                                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                            class="form-control form-control-solid rounded-3 @error('email') is-invalid @enderror">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label for="phone" class="form-label font-bold text-gray-800 fs-7 mb-2">Phone Number</label>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                            class="form-control form-control-solid rounded-3 @error('phone') is-invalid @enderror">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-6">
                                        <label for="company_name" class="form-label font-bold text-gray-800 fs-7 mb-2">Company Name</label>
                                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                                            class="form-control form-control-solid rounded-3 @error('company_name') is-invalid @enderror">
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-end gap-3 mt-8">
                                        <a href="{{ route('profile.show') }}" class="btn btn-light font-bold px-5">Cancel</a>
                                        <button type="submit" class="btn btn-primary font-bold px-6">
                                            <i class="ki-outline ki-check me-1 fs-5"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Guidance Sidebar Column -->
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8 bg-light-soft" style="border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0 bg-transparent">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Account Guidance</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 fs-7 text-gray-600">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="ki-outline ki-information-5 fs-4 text-primary mt-1"></i>
                                        <span>Keep profile contact details up to date to ensure account security alerts reach you.</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="ki-outline ki-phone fs-4 text-primary mt-1"></i>
                                        <span>Your phone number is used for priority notifications and account recovery verification.</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="ki-outline ki-briefcase fs-4 text-primary mt-1"></i>
                                        <span>Your company name will automatically populate on generated receipts and billing records.</span>
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