@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10">
                    <div class="col-xl-8">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Create New User</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">
                                        <i class="ki-outline ki-arrow-left fs-2"></i>Back to Users
                                    </a>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if($errors->any())
                                    <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                        <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-danger">Error</h4>
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="separator my-10"></div>

                                <form method="POST" action="{{ route('admin.users.store') }}" class="form">
                                    @csrf
                                    
                                    <!--begin::Input group-->
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Enter user's full name" value="{{ old('name') }}" required />
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    
                                    <!--begin::Input group-->
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
                                        <div class="col-lg-8">
                                            <input type="email" name="email" class="form-control form-control-solid @error('email') is-invalid @enderror" placeholder="Enter user's email" value="{{ old('email') }}" required />
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    
                                    <!--begin::Input group-->
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Phone Number</label>
                                        <div class="col-lg-8">
                                            <input type="tel" name="phone" class="form-control form-control-solid @error('phone') is-invalid @enderror" placeholder="Enter user's phone number" value="{{ old('phone') }}" />
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    
                                    <!--begin::Input group-->
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Company Name</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="company_name" class="form-control form-control-solid @error('company_name') is-invalid @enderror" placeholder="Enter user's company name" value="{{ old('company_name') }}" />
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    
                                    <!--begin::Input group-->
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password</label>
                                        <div class="col-lg-8">
                                            <input type="password" name="password" class="form-control form-control-solid @error('password') is-invalid @enderror" placeholder="Enter password" required />
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    
                                    <!--begin::Input group-->
                                    <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Confirm Password</label>
                                        <div class="col-lg-8">
                                            <input type="password" name="password_confirmation" class="form-control form-control-solid" placeholder="Confirm password" required />
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                    
                                    <div class="separator separator-dashed my-10"></div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-light me-3">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <span class="indicator-label">Create User</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    
                    <div class="col-xl-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bold">User Roles</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column">
                                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                        <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                                        <div class="d-flex flex-stack flex-grow-1">
                                            <div class="fw-semibold">
                                                <h4 class="text-gray-900 fw-bold">Note</h4>
                                                <div class="fs-6 text-gray-700">Assign one or more roles to control what this user can access in the system.</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-10">
                                        @foreach($roles as $role)
                                            <div class="form-check form-check-custom form-check-solid mb-5">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }} />
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        @endforeach
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