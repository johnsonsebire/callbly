@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Main Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Sender Names</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage your SMS sender IDs</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger mb-3">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="row g-5 g-xl-10">
                            <div class="col-xl-8">
                                <!-- Sender Names List Card -->
                                @if($senderNames->isEmpty())
                                    <div class="text-center my-5">
                                        <i class="ki-outline ki-abstract-26 fs-2tx text-gray-300 mb-5"></i>
                                        <p class="lead">You haven't registered any sender names yet.</p>
                                        <p class="text-muted">A sender name allows recipients to identify who sent the message.</p>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-150px">Sender ID</th>
                                                    <th class="min-w-100px">Status</th>
                                                    <th class="min-w-120px">Created Date</th>
                                                    <th class="min-w-200px">Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($senderNames as $senderName)
                                                    <tr>
                                                        <td><strong>{{ $senderName->name }}</strong></td>
                                                        <td>
                                                            @if($senderName->status == 'approved')
                                                                <span class="badge badge-light-success fs-7 fw-bold">Approved</span>
                                                            @elseif($senderName->status == 'rejected')
                                                                <span class="badge badge-light-danger fs-7 fw-bold">Rejected</span>
                                                            @else
                                                                <span class="badge badge-light-warning fs-7 fw-bold">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $senderName->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($senderName->status == 'pending')
                                                                <span class="text-warning">
                                                                    <i class="ki-outline ki-timer fs-2 me-1"></i> Awaiting approval
                                                                </span>
                                                            @elseif($senderName->status == 'rejected')
                                                                <span class="text-danger">
                                                                    <i class="ki-outline ki-cross-circle fs-2 me-1"></i> {{ $senderName->rejection_reason ?? 'Rejected by provider' }}
                                                                </span>
                                                            @else
                                                                <span class="text-success">
                                                                    <i class="ki-outline ki-check-circle fs-2 me-1"></i> Active and ready to use
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
                            
                            <div class="col-xl-4">
                                <!-- Register New Sender ID Card -->
                                <div class="card card-bordered mb-5">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Register New Sender ID</span>
                                        </h3>
                                    </div>
                                    <div class="card-body pt-5">
                                        <form method="POST" action="{{ route('sms.sender-names.store') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Sender Name</label>
                                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                                    value="{{ old('name') }}" maxlength="11" required>
                                                <small class="text-muted">
                                                    Sender name should be 3-11 alphanumeric characters (A-Z, 0-9)
                                                </small>
                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ki-outline ki-plus-circle fs-2 me-2"></i>Register Sender ID
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Guidelines Card -->
                                <div class="card card-bordered">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Registration Guidelines</span>
                                        </h3>
                                        <div class="card-toolbar">
                                            <i class="ki-outline ki-abstract-26 fs-2 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-5">
                                        <ul class="list-unstyled">
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="bullet bullet-dot bg-success me-2"></span>
                                                <span class="text-gray-700 fs-6">3-11 characters long</span>
                                            </li>
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="bullet bullet-dot bg-success me-2"></span>
                                                <span class="text-gray-700 fs-6">Only alphanumeric characters (A-Z, 0-9)</span>
                                            </li>
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="bullet bullet-dot bg-danger me-2"></span>
                                                <span class="text-gray-700 fs-6">No spaces or special characters</span>
                                            </li>
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="bullet bullet-dot bg-info me-2"></span>
                                                <span class="text-gray-700 fs-6">Approval takes 1-3 business days</span>
                                            </li>
                                            <li class="d-flex align-items-center mb-3">
                                                <span class="bullet bullet-dot bg-primary me-2"></span>
                                                <span class="text-gray-700 fs-6">Brand names should match your business</span>
                                            </li>
                                            <li class="d-flex align-items-center">
                                                <span class="bullet bullet-dot bg-warning me-2"></span>
                                                <span class="text-gray-700 fs-6">Generic terms like "INFO" may be rejected</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
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