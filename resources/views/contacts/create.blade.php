@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Create Contact Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Create New Contact</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Add a new contact to your address book</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Contacts
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

                        <form action="{{ route('contacts.store') }}" method="POST">
                            @csrf
                            
                            <!-- Basic Information Section -->
                            <div class="card card-bordered mb-6">
                                <div class="card-header">
                                    <h3 class="card-title">Basic Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label required">First Name</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label required">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone_number" class="form-label required">Phone Number</label>
                                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                                                <div class="form-text">Phone numbers will automatically be formatted with the country code (e.g. 233).</div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="alternative_phone" class="form-label">Alternative Phone</label>
                                                <input type="text" class="form-control" id="alternative_phone" name="alternative_phone" value="{{ old('alternative_phone') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                                <input type="text" class="form-control" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}">
                                                <div class="form-text">Leave blank to use primary phone number for WhatsApp</div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="alternative_email" class="form-label">Alternative Email</label>
                                                <input type="email" class="form-control" id="alternative_email" name="alternative_email" value="{{ old('alternative_email') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="website" class="form-label">Website</label>
                                                <input type="url" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="https://example.com">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Information Section -->
                            <div class="card card-bordered mb-6">
                                <div class="card-header">
                                    <h3 class="card-title">Professional Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="company" class="form-label">Company</label>
                                                <input type="text" class="form-control" id="company" name="company" value="{{ old('company') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="job_title" class="form-label">Job Title</label>
                                                <input type="text" class="form-control" id="job_title" name="job_title" value="{{ old('job_title') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="department" class="form-label">Department</label>
                                                <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="industry" class="form-label">Industry</label>
                                                <input type="text" class="form-control" id="industry" name="industry" value="{{ old('industry') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="annual_revenue" class="form-label">Annual Revenue (GHS)</label>
                                                <input type="number" class="form-control" id="annual_revenue" name="annual_revenue" value="{{ old('annual_revenue') }}" step="0.01" min="0">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="company_size" class="form-label">Company Size (Employees)</label>
                                                <input type="number" class="form-control" id="company_size" name="company_size" value="{{ old('company_size') }}" min="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- CRM Information Section -->
                            <div class="card card-bordered mb-6">
                                <div class="card-header">
                                    <h3 class="card-title">CRM Information</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="lead_status" class="form-label">Lead Status</label>
                                                <select class="form-select" id="lead_status" name="lead_status">
                                                    <option value="new" {{ old('lead_status') == 'new' ? 'selected' : '' }}>New</option>
                                                    <option value="contacted" {{ old('lead_status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                    <option value="qualified" {{ old('lead_status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                                    <option value="proposal" {{ old('lead_status') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                                    <option value="negotiation" {{ old('lead_status') == 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                                    <option value="closed_won" {{ old('lead_status') == 'closed_won' ? 'selected' : '' }}>Closed Won</option>
                                                    <option value="closed_lost" {{ old('lead_status') == 'closed_lost' ? 'selected' : '' }}>Closed Lost</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="priority" class="form-label">Priority</label>
                                                <select class="form-select" id="priority" name="priority">
                                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="lead_source" class="form-label">Lead Source</label>
                                                <input type="text" class="form-control" id="lead_source" name="lead_source" value="{{ old('lead_source') }}" placeholder="Website, Referral, Social Media, etc.">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="potential_value" class="form-label">Potential Value (GHS)</label>
                                                <input type="number" class="form-control" id="potential_value" name="potential_value" value="{{ old('potential_value') }}" step="0.01" min="0">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="preferred_contact_method" class="form-label">Preferred Contact Method</label>
                                                <select class="form-select" id="preferred_contact_method" name="preferred_contact_method">
                                                    <option value="phone" {{ old('preferred_contact_method', 'phone') == 'phone' ? 'selected' : '' }}>Phone</option>
                                                    <option value="email" {{ old('preferred_contact_method') == 'email' ? 'selected' : '' }}>Email</option>
                                                    <option value="whatsapp" {{ old('preferred_contact_method') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                                    <option value="sms" {{ old('preferred_contact_method') == 'sms' ? 'selected' : '' }}>SMS</option>
                                                </select>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="last_contact_date" class="form-label">Last Contact Date</label>
                                                <input type="date" class="form-control" id="last_contact_date" name="last_contact_date" value="{{ old('last_contact_date') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="next_follow_up_date" class="form-label">Next Follow-up Date</label>
                                                <input type="date" class="form-control" id="next_follow_up_date" name="next_follow_up_date" value="{{ old('next_follow_up_date') }}">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="tags" class="form-label">Tags</label>
                                                <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="VIP, Hot Lead, etc. (comma separated)">
                                                <div class="form-text">Separate multiple tags with commas</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media & Location Section -->
                            <div class="card card-bordered mb-6">
                                <div class="card-header">
                                    <h3 class="card-title">Social Media & Location</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-muted mb-4">Social Media Profiles</h6>
                                            
                                            <div class="mb-3">
                                                <label for="linkedin_profile" class="form-label">LinkedIn Profile</label>
                                                <input type="url" class="form-control" id="linkedin_profile" name="linkedin_profile" value="{{ old('linkedin_profile') }}" placeholder="https://linkedin.com/in/username">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="twitter_handle" class="form-label">Twitter Handle</label>
                                                <input type="text" class="form-control" id="twitter_handle" name="twitter_handle" value="{{ old('twitter_handle') }}" placeholder="@username">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="facebook_profile" class="form-label">Facebook Profile</label>
                                                <input type="url" class="form-control" id="facebook_profile" name="facebook_profile" value="{{ old('facebook_profile') }}" placeholder="https://facebook.com/username">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="instagram_handle" class="form-label">Instagram Handle</label>
                                                <input type="text" class="form-control" id="instagram_handle" name="instagram_handle" value="{{ old('instagram_handle') }}" placeholder="@username">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <h6 class="fw-bold text-muted mb-4">Address Information</h6>
                                            
                                            <div class="mb-3">
                                                <label for="address" class="form-label">Street Address</label>
                                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="city" class="form-label">City</label>
                                                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="state" class="form-label">State/Region</label>
                                                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="postal_code" class="form-label">Postal Code</label>
                                                        <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="country" class="form-label">Country</label>
                                                        <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Ghana') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="timezone" class="form-label">Timezone</label>
                                                <select class="form-select" id="timezone" name="timezone">
                                                    <option value="">Select Timezone</option>
                                                    <option value="Africa/Accra" {{ old('timezone', 'Africa/Accra') == 'Africa/Accra' ? 'selected' : '' }}>Africa/Accra (GMT)</option>
                                                    <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                    <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                                    <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            <div class="card card-bordered mb-6">
                                <div class="card-header">
                                    <h3 class="card-title">Notes</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">Public Notes</label>
                                                <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                                                <div class="form-text">Notes visible to all team members</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="internal_notes" class="form-label">Internal Notes</label>
                                                <textarea class="form-control" id="internal_notes" name="internal_notes" rows="4">{{ old('internal_notes') }}</textarea>
                                                <div class="form-text">Private notes for internal use only</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Groups Section -->
                            @if(count($groups) > 0)
                            <div class="card card-bordered mb-6">
                                <div class="card-header">
                                    <h3 class="card-title">Contact Groups</h3>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($groups as $id => $name)
                                            <div class="form-check form-check-custom form-check-solid me-5">
                                                <input class="form-check-input" type="checkbox" id="group_{{ $id }}" name="groups[]" value="{{ $id }}">
                                                <label class="form-check-label" for="group_{{ $id }}">{{ $name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('contacts.index') }}" class="btn btn-light me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-check fs-2 me-2"></i>Create Contact
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
@push('scripts')
<script>
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Format phone numbers with country code
    $('#phone_number, #alternative_phone, #whatsapp_number').on('input', function() {
        let value = $(this).val().replace(/\D/g, ''); // Remove non-numeric characters
        if (value.length > 0) {
            $(this).val(`233${value}`); // Prepend Ghana country code
        }
    });
    // Handle form submission
    $('form').on('submit', function(e) {
        // Validate phone numbers
        let phoneNumber = $('#phone_number').val();
        if (phoneNumber && !/^\d{10,15}$/.test(phoneNumber)) {
            e.preventDefault();
            alert('Please enter a valid phone number (10-15 digits).');
            return false;
        }
        
        let whatsappNumber = $('#whatsapp_number').val();
        if (whatsappNumber && !/^\d{10,15}$/.test(whatsappNumber)) {
            e.preventDefault();
            alert('Please enter a valid WhatsApp number (10-15 digits).');
            return false;
        }
    });
</script>
@endpush