@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card">
                    <div class="card-body p-12">
                        <div class="text-center">
                            <h1 class="text-dark mb-3 fs-1">Access Denied</h1>
                            
                            <div class="fw-semibold fs-6 text-gray-500 mb-7">
                                You do not have permission to access this resource.
                            </div>
                            
                            <div class="mb-11">
                                <img src="{{ asset('assets/media/illustrations/sketchy-1/18.png') }}" class="mw-100 mh-300px" alt="Error 403">
                            </div>
                            
                            <div class="mb-0">
                                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">Return to Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
