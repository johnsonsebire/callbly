@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Contact Details Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Contact Details</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">View contact information</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-light-primary me-2">
                                <i class="ki-outline ki-pencil fs-2 me-2"></i> Edit
                            </a>
                            <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                    <div class="symbol-label bg-light-primary text-primary fs-2 fw-bold">
                                        {{ substr($contact->full_name, 0, 1) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="text-gray-900 fs-2 fw-bold me-1">{{ $contact->full_name }}</span>
                                        </div>
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-phone fs-4 me-1"></i>
                                                {{ $contact->phone_number }}
                                            </span>
                                            @if($contact->email)
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-sms fs-4 me-1"></i>
                                                {{ $contact->email }}
                                            </span>
                                            @endif
                                            @if($contact->company)
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-office-bag fs-4 me-1"></i>
                                                {{ $contact->company }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" class="btn btn-sm btn-primary me-2">
                                            <i class="ki-outline ki-message-text-2 fs-3"></i>
                                            Send SMS
                                        </a>
                                        <form action="{{ route('contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                <i class="ki-outline ki-trash fs-3"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Contact Information</span>
                                        </h3>
                                    </div>
                                    <div class="card-body py-5">
                                        <div class="table-responsive">
                                            <table class="table table-row-bordered gy-5">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-bold">Full Name</td>
                                                        <td>{{ $contact->full_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Phone Number</td>
                                                        <td>{{ $contact->phone_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Email</td>
                                                        <td>{{ $contact->email ?: 'Not specified' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Company</td>
                                                        <td>{{ $contact->company ?: 'Not specified' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Created</td>
                                                        <td>{{ $contact->created_at->format('M d, Y') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold">Last Updated</td>
                                                        <td>{{ $contact->updated_at->format('M d, Y') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Groups Card -->
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Contact Groups</span>
                                        </h3>
                                    </div>
                                    <div class="card-body py-5">
                                        @if($contact->groups->count() > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($contact->groups as $group)
                                                    <a href="{{ route('contact-groups.show', $group) }}" class="badge badge-lg badge-light-primary fs-7 fw-semibold mb-1">
                                                        <i class="ki-outline ki-people fs-7 me-1"></i>{{ $group->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-10">
                                                <i class="ki-outline ki-people fs-3tx text-gray-300 mb-5"></i>
                                                <div class="text-muted">This contact is not in any groups</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Notes Card -->
                                @if($contact->notes)
                                <div class="card card-bordered">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Notes</span>
                                        </h3>
                                    </div>
                                    <div class="card-body py-5">
                                        {{ $contact->notes }}
                                    </div>
                                </div>
                                @endif
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