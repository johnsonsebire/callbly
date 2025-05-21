@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Create Group Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Create Contact Group</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Create a new group to organize your contacts</span>
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

                        <form action="{{ route('contact-groups.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label required fw-semibold">Group Name</label>
                                        <input type="text" class="form-control form-control-solid" id="name" name="name" value="{{ old('name') }}" required>
                                        <div class="text-muted fs-7 mt-1">Give your group a descriptive name</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                                        <textarea class="form-control form-control-solid" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                        <div class="text-muted fs-7 mt-1">Add details about this group of contacts</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card card-bordered bg-light h-100">
                                        <div class="card-header">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-dark">Group Information</span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-5">
                                                <div class="d-flex align-items-center mb-3">
                                                    <i class="ki-outline ki-information-5 fs-2 text-primary me-3"></i>
                                                    <div class="text-gray-800 fs-6 fw-semibold">Groups help you organize contacts</div>
                                                </div>
                                                <div class="d-flex align-items-center mb-3">
                                                    <i class="ki-outline ki-message-text-2 fs-2 text-primary me-3"></i>
                                                    <div class="text-gray-800 fs-6 fw-semibold">Send messages to entire groups at once</div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-abstract-26 fs-2 text-primary me-3"></i>
                                                    <div class="text-gray-800 fs-6 fw-semibold">Track performance metrics by group</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-5">
                                <a href="{{ route('contact-groups.index') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-check fs-2 me-2"></i>Create Group
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