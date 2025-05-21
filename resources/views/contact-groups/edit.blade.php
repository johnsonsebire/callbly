@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Edit Group Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Edit Contact Group</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Update your contact group details</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contact-groups.index') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Groups
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('contact-groups.update', $group->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label required fw-semibold">Group Name</label>
                                        <input type="text" class="form-control form-control-solid" id="name" name="name" value="{{ old('name', $group->name) }}" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                                        <textarea class="form-control form-control-solid" id="description" name="description" rows="5">{{ old('description', $group->description) }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card card-bordered bg-light-success h-100">
                                        <div class="card-header">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-dark">Group Summary</span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex flex-column mb-5">
                                                <div class="d-flex align-items-center mb-5">
                                                    <div class="symbol symbol-50px me-5">
                                                        <span class="symbol-label bg-light-primary">
                                                            <i class="ki-outline ki-people fs-2x text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fs-6 fw-bold">{{ $group->contacts->count() }} contacts</div>
                                                        <div class="text-gray-600 fs-7">in this group</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px me-5">
                                                        <span class="symbol-label bg-light-primary">
                                                            <i class="ki-outline ki-calendar fs-2x text-primary"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-gray-800 fs-6 fw-bold">Created on</div>
                                                        <div class="text-gray-600 fs-7">{{ $group->created_at->format('M d, Y') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="separator my-5"></div>
                                            
                                            <div class="text-center">
                                                <a href="{{ route('contact-groups.show', $group) }}" class="btn btn-light-primary btn-sm">
                                                    <i class="ki-outline ki-eye fs-2 me-2"></i>View Group Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-5">
                                <a href="{{ route('contact-groups.index') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-check fs-2 me-2"></i>Update Group
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