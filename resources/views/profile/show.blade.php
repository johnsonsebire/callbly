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
                                    <h2 class="fw-bold">Profile Information</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                                        <i class="ki-outline ki-pencil fs-2"></i>Edit Profile
                                    </a>
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
                                
                                <div class="separator my-10"></div>

                                <!--begin::Details-->
                                <div class="d-flex flex-wrap py-5">
                                    <!--begin::Row-->
                                    <div class="flex-equal me-5">
                                        <!--begin::Details-->
                                        <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                            <!--begin::Row-->
                                            <tr>
                                                <td class="text-gray-400 min-w-175px w-175px">Name:</td>
                                                <td class="text-gray-800">{{ $user->name }}</td>
                                            </tr>
                                            <!--end::Row-->
                                            <!--begin::Row-->
                                            <tr>
                                                <td class="text-gray-400">Email:</td>
                                                <td class="text-gray-800">{{ $user->email }}</td>
                                            </tr>
                                            <!--end::Row-->
                                            <!--begin::Row-->
                                            <tr>
                                                <td class="text-gray-400">Phone:</td>
                                                <td class="text-gray-800">{{ $user->phone ?? 'Not set' }}</td>
                                            </tr>
                                            <!--end::Row-->
                                            <!--begin::Row-->
                                            <tr>
                                                <td class="text-gray-400">Company Name:</td>
                                                <td class="text-gray-800">{{ $user->company_name ?? 'Not set' }}</td>
                                            </tr>
                                            <!--end::Row-->
                                        </table>
                                        <!--end::Details-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Details-->
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
                                    <h2 class="fw-bold">Account Status</h2>
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
                                                <span class="symbol-label bg-light-success">
                                                    <i class="ki-outline ki-shield-tick fs-3 text-success"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Email Verified</a>
                                                <span class="text-muted fw-bold">{{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    <div class="separator separator-dashed my-5"></div>
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-5">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-calendar fs-3 text-primary"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Member Since</a>
                                                <span class="text-muted fw-bold">{{ $user->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
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