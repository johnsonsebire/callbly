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
                                        {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                                {{ $contact->full_name }}
                                            </a>
                                        </div>
                                        
                                        <!-- CRM Status Badges -->
                                        <x-contact.crm-badges :contact="$contact" />
                                        
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            @if($contact->job_title)
                                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                    <i class="ki-outline ki-profile-circle fs-4 me-1"></i>{{ $contact->job_title }}
                                                </a>
                                            @endif
                                            @if($contact->company)
                                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                                    <i class="ki-outline ki-geolocation fs-4 me-1"></i>{{ $contact->company }}
                                                </a>
                                            @endif
                                            @if($contact->email)
                                                <a href="mailto:{{ $contact->email }}" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                                    <i class="ki-outline ki-sms fs-4 me-1"></i>{{ $contact->email }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex my-4">
                                        @if($contact->phone_number)
                                            <a href="tel:{{ $contact->phone_number }}" class="btn btn-sm btn-light me-2">
                                                <i class="ki-outline ki-phone fs-3"></i> Call
                                            </a>
                                        @endif
                                        @if($contact->has_whatsapp)
                                            <a href="#" onclick="openWhatsApp('{{ $contact->whatsapp_number ?: $contact->phone_number }}')" class="btn btn-sm btn-success me-2">
                                                <i class="fab fa-whatsapp fs-3"></i> WhatsApp
                                            </a>
                                        @endif
                                        @if($contact->email)
                                            <a href="mailto:{{ $contact->email }}" class="btn btn-sm btn-light-primary">
                                                <i class="ki-outline ki-sms fs-3"></i> Email
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title">Basic Information</h3>
                                    </div>
                                    <div class="card-body py-5">
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                                <tbody>
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Phone Number:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->phone_number }}</td>
                                                    </tr>
                                                    @if($contact->alternative_phone)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Alt. Phone:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->alternative_phone }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->whatsapp_number)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">WhatsApp:</td>
                                                        <td class="text-gray-900 fw-bold">
                                                            {{ $contact->whatsapp_number }}
                                                            @if($contact->has_whatsapp)
                                                                <span class="badge badge-light-success ms-2">Verified</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->email)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Email:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->email }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->alternative_email)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Alt. Email:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->alternative_email }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->date_of_birth)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Date of Birth:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->date_of_birth->format('F j, Y') }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->website)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Website:</td>
                                                        <td class="text-gray-900 fw-bold">
                                                            <a href="{{ $contact->website }}" target="_blank" class="text-primary">
                                                                {{ $contact->website }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Professional Information -->
                                @if($contact->company || $contact->job_title || $contact->department || $contact->industry || $contact->annual_revenue || $contact->company_size)
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title">Professional Information</h3>
                                    </div>
                                    <div class="card-body py-5">
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                                <tbody>
                                                    @if($contact->company)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Company:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->company }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->job_title)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Job Title:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->job_title }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->department)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Department:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->department }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->industry)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Industry:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->industry }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->annual_revenue)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Annual Revenue:</td>
                                                        <td class="text-gray-900 fw-bold">GHS {{ number_format($contact->annual_revenue, 2) }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->company_size)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Company Size:</td>
                                                        <td class="text-gray-900 fw-bold">{{ number_format($contact->company_size) }} employees</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <!-- CRM Information -->
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title">CRM Information</h3>
                                    </div>
                                    <div class="card-body py-5">
                                        <div class="table-responsive">
                                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                                <tbody>
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Lead Status:</td>
                                                        <td class="text-gray-900 fw-bold">
                                                            <span class="badge bg-{{ $contact->lead_status_color }}">
                                                                {{ ucfirst(str_replace('_', ' ', $contact->lead_status)) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Priority:</td>
                                                        <td class="text-gray-900 fw-bold">
                                                            <span class="badge bg-{{ $contact->priority_color }}">
                                                                {{ ucfirst($contact->priority) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    @if($contact->lead_source)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Lead Source:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->lead_source }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->potential_value)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Potential Value:</td>
                                                        <td class="text-gray-900 fw-bold">GHS {{ number_format($contact->potential_value, 2) }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->last_contact_date)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Last Contact:</td>
                                                        <td class="text-gray-900 fw-bold">{{ $contact->last_contact_date->format('M j, Y') }}</td>
                                                    </tr>
                                                    @endif
                                                    @if($contact->next_follow_up_date)
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Next Follow-up:</td>
                                                        <td class="text-gray-900 fw-bold">
                                                            {{ $contact->next_follow_up_date->format('M j, Y') }}
                                                            @if($contact->isOverdueForFollowUp())
                                                                <span class="badge badge-light-danger ms-2">Overdue</span>
                                                            @elseif($contact->needsFollowUp())
                                                                <span class="badge badge-light-warning ms-2">Due Today</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr>
                                                        <td class="min-w-125px fw-bold text-muted">Contact Method:</td>
                                                        <td class="text-gray-900 fw-bold">{{ ucfirst($contact->preferred_contact_method) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Social Media & Address -->
                                @if($contact->linkedin_profile || $contact->twitter_handle || $contact->facebook_profile || $contact->instagram_handle || $contact->address)
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title">Social Media & Location</h3>
                                    </div>
                                    <div class="card-body py-5">
                                        @if($contact->linkedin_profile || $contact->twitter_handle || $contact->facebook_profile || $contact->instagram_handle)
                                        <div class="mb-5">
                                            <h6 class="fw-bold text-muted mb-3">Social Media</h6>
                                            <div class="d-flex flex-wrap gap-3">
                                                @if($contact->linkedin_profile)
                                                <a href="{{ $contact->linkedin_profile }}" target="_blank" class="btn btn-light-primary btn-sm">
                                                    <i class="fab fa-linkedin me-2"></i>LinkedIn
                                                </a>
                                                @endif
                                                @if($contact->twitter_handle)
                                                <a href="https://twitter.com/{{ ltrim($contact->twitter_handle, '@') }}" target="_blank" class="btn btn-light-info btn-sm">
                                                    <i class="fab fa-twitter me-2"></i>Twitter
                                                </a>
                                                @endif
                                                @if($contact->facebook_profile)
                                                <a href="{{ $contact->facebook_profile }}" target="_blank" class="btn btn-light-primary btn-sm">
                                                    <i class="fab fa-facebook me-2"></i>Facebook
                                                </a>
                                                @endif
                                                @if($contact->instagram_handle)
                                                <a href="https://instagram.com/{{ ltrim($contact->instagram_handle, '@') }}" target="_blank" class="btn btn-light-danger btn-sm">
                                                    <i class="fab fa-instagram me-2"></i>Instagram
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($contact->address || $contact->city || $contact->state || $contact->country)
                                        <div>
                                            <h6 class="fw-bold text-muted mb-3">Address</h6>
                                            <div class="text-gray-900">
                                                @if($contact->address)
                                                    {{ $contact->address }}<br>
                                                @endif
                                                @if($contact->city || $contact->state || $contact->postal_code)
                                                    {{ $contact->city }}@if($contact->city && ($contact->state || $contact->postal_code)), @endif
                                                    {{ $contact->state }} {{ $contact->postal_code }}<br>
                                                @endif
                                                @if($contact->country)
                                                    {{ $contact->country }}
                                                @endif
                                                @if($contact->timezone)
                                                    <br><small class="text-muted">Timezone: {{ $contact->timezone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Groups Card -->
                                @if($contact->groups->count() > 0)
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title">Contact Groups</h3>
                                    </div>
                                    <div class="card-body py-5">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($contact->groups as $group)
                                                <span class="badge badge-light-primary fs-7 fw-semibold">{{ $group->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Notes Card -->
                                @if($contact->notes || $contact->internal_notes)
                                <div class="card card-bordered">
                                    <div class="card-header">
                                        <h3 class="card-title">Notes</h3>
                                    </div>
                                    <div class="card-body py-5">
                                        @if($contact->notes)
                                        <div class="mb-4">
                                            <h6 class="fw-bold text-muted mb-2">Public Notes</h6>
                                            <p class="text-gray-900 m-0">{{ $contact->notes }}</p>
                                        </div>
                                        @endif
                                        @if($contact->internal_notes)
                                        <div>
                                            <h6 class="fw-bold text-muted mb-2">Internal Notes</h6>
                                            <p class="text-gray-600 m-0">{{ $contact->internal_notes }}</p>
                                        </div>
                                        @endif
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

@push('scripts')
<script>
function openWhatsApp(phoneNumber, message = '') {
    const url = `https://wa.me/${phoneNumber.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
}
</script>
@endpush
@endsection