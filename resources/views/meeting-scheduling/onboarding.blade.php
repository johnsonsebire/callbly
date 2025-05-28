@extends('layouts.master')

@section('title', 'Meeting Scheduling Setup - Onboarding')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Progress Bar -->
                        <div class="card mb-5">
                            <div class="card-body py-10">
                                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid gap-10" id="onboarding_stepper">
                                    <!-- Step 1 -->
                                    <div class="flex-row-fluid py-lg-5 px-lg-15">
                                        <div class="stepper-nav">
                                            <div class="stepper-item current" data-kt-stepper-element="nav" data-step="1">
                                                <div class="stepper-wrapper">
                                                    <div class="stepper-icon w-40px h-40px">
                                                        <i class="ki-outline ki-building fs-2 stepper-check"></i>
                                                        <span class="stepper-number">1</span>
                                                    </div>
                                                    <div class="stepper-label">
                                                        <h3 class="stepper-title">Company Profile</h3>
                                                        <div class="stepper-desc fw-semibold">Setup your company information</div>
                                                    </div>
                                                </div>
                                                <div class="stepper-line h-40px"></div>
                                            </div>

                                            <div class="stepper-item" data-kt-stepper-element="nav" data-step="2">
                                                <div class="stepper-wrapper">
                                                    <div class="stepper-icon w-40px h-40px">
                                                        <i class="ki-outline ki-calendar fs-2 stepper-check"></i>
                                                        <span class="stepper-number">2</span>
                                                    </div>
                                                    <div class="stepper-label">
                                                        <h3 class="stepper-title">First Event Type</h3>
                                                        <div class="stepper-desc fw-semibold">Create your first meeting type</div>
                                                    </div>
                                                </div>
                                                <div class="stepper-line h-40px"></div>
                                            </div>

                                            <div class="stepper-item" data-kt-stepper-element="nav" data-step="3">
                                                <div class="stepper-wrapper">
                                                    <div class="stepper-icon w-40px h-40px">
                                                        <i class="ki-outline ki-time fs-2 stepper-check"></i>
                                                        <span class="stepper-number">3</span>
                                                    </div>
                                                    <div class="stepper-label">
                                                        <h3 class="stepper-title">Availability</h3>
                                                        <div class="stepper-desc fw-semibold">Set your available hours</div>
                                                    </div>
                                                </div>
                                                <div class="stepper-line h-40px"></div>
                                            </div>

                                            <div class="stepper-item" data-kt-stepper-element="nav" data-step="4">
                                                <div class="stepper-wrapper">
                                                    <div class="stepper-icon w-40px h-40px">
                                                        <i class="ki-outline ki-check fs-1 stepper-check"></i>
                                                        <span class="stepper-number">4</span>
                                                    </div>
                                                    <div class="stepper-label">
                                                        <h3 class="stepper-title">Complete</h3>
                                                        <div class="stepper-desc fw-semibold">Setup completed successfully</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="card">
                            <div class="card-body">
                                <form id="onboarding_form" method="POST" action="{{ route('meeting-scheduling.onboarding.save') }}" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <!-- Step 1: Company Profile -->
                                    <div class="flex-column current" data-kt-stepper-element="content" data-step="1">
                                        <div class="w-100">
                                            <div class="mb-10 text-center">
                                                <h2 class="text-gray-900 fw-bold mb-3">Company Profile</h2>
                                                <div class="text-gray-600 fw-semibold fs-6">
                                                    Let's start by setting up your company information. This will be displayed on your public scheduling pages.
                                                </div>
                                            </div>

                                            <div class="row g-9 mb-8">
                                                <!-- Company Name -->
                                                <div class="col-md-6">
                                                    <label class="required fs-6 fw-semibold mb-2">Company Name</label>
                                                    <input type="text" class="form-control" name="company_name" 
                                                           value="{{ old('company_name') }}" 
                                                           placeholder="e.g., Acme Consulting" required>
                                                    @error('company_name')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Brand Name -->
                                                <div class="col-md-6">
                                                    <label class="required fs-6 fw-semibold mb-2">Brand Name (URL)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">{{ url('/') }}/</span>
                                                        <input type="text" class="form-control" name="brand_name" 
                                                               value="{{ old('brand_name') }}" 
                                                               placeholder="acme-consulting" 
                                                               pattern="[a-z0-9-]+" required>
                                                    </div>
                                                    <div class="form-text">Only lowercase letters, numbers, and hyphens allowed</div>
                                                    @error('brand_name')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Description -->
                                                <div class="col-12">
                                                    <label class="fs-6 fw-semibold mb-2">Description</label>
                                                    <textarea class="form-control" name="description" rows="3" 
                                                              placeholder="Brief description of your company and services">{{ old('description') }}</textarea>
                                                    @error('description')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Logo Upload -->
                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Company Logo</label>
                                                    <input type="file" class="form-control" name="logo" accept="image/*">
                                                    <div class="form-text">Recommended: Square image, max 2MB</div>
                                                    @error('logo')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Primary Color -->
                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Brand Color</label>
                                                    <input type="color" class="form-control form-control-color" 
                                                           name="primary_color" value="{{ old('primary_color', '#3B82F6') }}">
                                                    <div class="form-text">Choose your brand's primary color</div>
                                                    @error('primary_color')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Contact Information -->
                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Email</label>
                                                    <input type="email" class="form-control" name="email" 
                                                           value="{{ old('email', auth()->user()->email) }}" 
                                                           placeholder="contact@company.com">
                                                    @error('email')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Phone</label>
                                                    <input type="tel" class="form-control" name="phone" 
                                                           value="{{ old('phone', auth()->user()->phone) }}" 
                                                           placeholder="+233 XX XXX XXXX">
                                                    @error('phone')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Website -->
                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Website</label>
                                                    <input type="url" class="form-control" name="website" 
                                                           value="{{ old('website') }}" 
                                                           placeholder="https://www.company.com">
                                                    @error('website')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Address -->
                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Address</label>
                                                    <input type="text" class="form-control" name="address" 
                                                           value="{{ old('address') }}" 
                                                           placeholder="City, Country">
                                                    @error('address')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2: First Event Type -->
                                    <div class="flex-column" data-kt-stepper-element="content" data-step="2">
                                        <div class="w-100">
                                            <div class="mb-10 text-center">
                                                <h2 class="text-gray-900 fw-bold mb-3">Create Your First Meeting Type</h2>
                                                <div class="text-gray-600 fw-semibold fs-6">
                                                    Set up your first event type. You can create more later from your dashboard.
                                                </div>
                                            </div>

                                            <div class="row g-9 mb-8">
                                                <!-- Event Name -->
                                                <div class="col-md-6">
                                                    <label class="required fs-6 fw-semibold mb-2">Event Name</label>
                                                    <input type="text" class="form-control" name="event_name" 
                                                           value="{{ old('event_name', '30-minute Consultation') }}" 
                                                           placeholder="e.g., 30-minute Consultation" required>
                                                    @error('event_name')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Duration -->
                                                <div class="col-md-6">
                                                    <label class="required fs-6 fw-semibold mb-2">Duration (minutes)</label>
                                                    <select class="form-select" name="duration" required>
                                                        <option value="15" {{ old('duration') == '15' ? 'selected' : '' }}>15 minutes</option>
                                                        <option value="30" {{ old('duration', '30') == '30' ? 'selected' : '' }}>30 minutes</option>
                                                        <option value="45" {{ old('duration') == '45' ? 'selected' : '' }}>45 minutes</option>
                                                        <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>1 hour</option>
                                                        <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>1.5 hours</option>
                                                        <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>2 hours</option>
                                                    </select>
                                                    @error('duration')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Event Description -->
                                                <div class="col-12">
                                                    <label class="fs-6 fw-semibold mb-2">Description</label>
                                                    <textarea class="form-control" name="event_description" rows="3" 
                                                              placeholder="Describe what this meeting is about">{{ old('event_description') }}</textarea>
                                                    @error('event_description')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Buffer Time -->
                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Buffer Time Before (minutes)</label>
                                                    <select class="form-select" name="buffer_before">
                                                        <option value="0" {{ old('buffer_before', '0') == '0' ? 'selected' : '' }}>No buffer</option>
                                                        <option value="5" {{ old('buffer_before') == '5' ? 'selected' : '' }}>5 minutes</option>
                                                        <option value="10" {{ old('buffer_before') == '10' ? 'selected' : '' }}>10 minutes</option>
                                                        <option value="15" {{ old('buffer_before') == '15' ? 'selected' : '' }}>15 minutes</option>
                                                    </select>
                                                    @error('buffer_before')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="fs-6 fw-semibold mb-2">Buffer Time After (minutes)</label>
                                                    <select class="form-select" name="buffer_after">
                                                        <option value="0" {{ old('buffer_after', '0') == '0' ? 'selected' : '' }}>No buffer</option>
                                                        <option value="5" {{ old('buffer_after') == '5' ? 'selected' : '' }}>5 minutes</option>
                                                        <option value="10" {{ old('buffer_after') == '10' ? 'selected' : '' }}>10 minutes</option>
                                                        <option value="15" {{ old('buffer_after') == '15' ? 'selected' : '' }}>15 minutes</option>
                                                    </select>
                                                    @error('buffer_after')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Meeting Type -->
                                                <div class="col-md-6">
                                                    <label class="required fs-6 fw-semibold mb-2">Meeting Type</label>
                                                    <select class="form-select" name="meeting_type" required>
                                                        <option value="one_on_one" {{ old('meeting_type', 'one_on_one') == 'one_on_one' ? 'selected' : '' }}>One-on-One</option>
                                                        <option value="group" {{ old('meeting_type') == 'group' ? 'selected' : '' }}>Group Meeting</option>
                                                    </select>
                                                    @error('meeting_type')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Max Attendees (for group meetings) -->
                                                <div class="col-md-6" id="max_attendees_field" style="display: none;">
                                                    <label class="fs-6 fw-semibold mb-2">Max Attendees</label>
                                                    <input type="number" class="form-control" name="max_attendees" 
                                                           value="{{ old('max_attendees', 10) }}" min="2" max="100">
                                                    @error('max_attendees')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 3: Availability -->
                                    <div class="flex-column" data-kt-stepper-element="content" data-step="3">
                                        <div class="w-100">
                                            <div class="mb-10 text-center">
                                                <h2 class="text-gray-900 fw-bold mb-3">Set Your Availability</h2>
                                                <div class="text-gray-600 fw-semibold fs-6">
                                                    Choose when you're available for meetings. You can always update this later.
                                                </div>
                                            </div>

                                            <div class="row g-9 mb-8">
                                                <!-- Timezone -->
                                                <div class="col-12">
                                                    <label class="required fs-6 fw-semibold mb-2">Timezone</label>
                                                    <select class="form-select" name="timezone" required>
                                                        <option value="Africa/Accra" {{ old('timezone', 'Africa/Accra') == 'Africa/Accra' ? 'selected' : '' }}>Africa/Accra (GMT)</option>
                                                        <option value="UTC" {{ old('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                                        <option value="America/New_York" {{ old('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                                        <option value="Europe/London" {{ old('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                                    </select>
                                                    @error('timezone')
                                                        <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Weekly Availability -->
                                                <div class="col-12">
                                                    <label class="fs-6 fw-semibold mb-4">Weekly Availability</label>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Day</th>
                                                                    <th>Available</th>
                                                                    <th>Start Time</th>
                                                                    <th>End Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                                                <tr>
                                                                    <td class="fw-bold">{{ ucfirst($day) }}</td>
                                                                    <td>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input day-checkbox" type="checkbox" 
                                                                                   name="availability[{{ $day }}][enabled]" value="1" 
                                                                                   id="{{ $day }}_enabled"
                                                                                   {{ old("availability.{$day}.enabled", in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']) ? '1' : '0') ? 'checked' : '' }}>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="time" class="form-control time-input" 
                                                                               name="availability[{{ $day }}][start_time]" 
                                                                               value="{{ old("availability.{$day}.start_time", '09:00') }}"
                                                                               {{ old("availability.{$day}.enabled", in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']) ? '1' : '0') ? '' : 'disabled' }}>
                                                                    </td>
                                                                    <td>
                                                                        <input type="time" class="form-control time-input" 
                                                                               name="availability[{{ $day }}][end_time]" 
                                                                               value="{{ old("availability.{$day}.end_time", '17:00') }}"
                                                                               {{ old("availability.{$day}.enabled", in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']) ? '1' : '0') ? '' : 'disabled' }}>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4: Complete -->
                                    <div class="flex-column" data-kt-stepper-element="content" data-step="4">
                                        <div class="w-100 text-center">
                                            <div class="mb-10">
                                                <div class="d-inline-flex align-items-center justify-content-center w-80px h-80px bg-success rounded-3 mb-7">
                                                    <i class="ki-outline ki-check text-white fs-2x"></i>
                                                </div>
                                                <h2 class="text-gray-900 fw-bold mb-3">Setup Complete!</h2>
                                                <div class="text-gray-600 fw-semibold fs-6 mb-8">
                                                    Congratulations! Your Meeting Scheduling Service is now active.
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column gap-7 mb-10">
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-check-circle fs-1 text-success me-5"></i>
                                                    <div class="text-start">
                                                        <h5 class="mb-1">Company profile created</h5>
                                                        <p class="text-gray-600 mb-0">Your branded company page is ready</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-check-circle fs-1 text-success me-5"></i>
                                                    <div class="text-start">
                                                        <h5 class="mb-1">First event type set up</h5>
                                                        <p class="text-gray-600 mb-0">People can now book meetings with you</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-check-circle fs-1 text-success me-5"></i>
                                                    <div class="text-start">
                                                        <h5 class="mb-1">Availability configured</h5>
                                                        <p class="text-gray-600 mb-0">Your calendar is ready for bookings</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                                                <a href="{{ route('meeting-scheduling.dashboard') }}" class="btn btn-primary">
                                                    <i class="ki-outline ki-element-11 fs-2 me-2"></i>Go to Dashboard
                                                </a>
                                                <button type="button" class="btn btn-light-primary" id="view-profile-btn">
                                                    <i class="ki-outline ki-eye fs-2 me-2"></i>View My Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Navigation -->
                                    <div class="d-flex flex-stack pt-10">
                                        <div class="mr-2">
                                            <button type="button" class="btn btn-lg btn-light-primary me-3" data-kt-stepper-action="previous">
                                                <i class="ki-outline ki-arrow-left fs-4 me-1"></i>Back
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-lg btn-primary me-3" data-kt-stepper-action="submit">
                                                <span class="indicator-label">Submit
                                                <i class="ki-outline ki-arrow-right fs-3 ms-2 me-0"></i></span>
                                                <span class="indicator-progress">Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                            <button type="button" class="btn btn-lg btn-primary" data-kt-stepper-action="next">
                                                Continue
                                                <i class="ki-outline ki-arrow-right fs-4 ms-1 me-0"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Stepper
    const stepperElement = document.querySelector('#onboarding_stepper');
    const stepper = new KTStepper(stepperElement);

    // Handle meeting type change
    const meetingTypeSelect = document.querySelector('select[name="meeting_type"]');
    const maxAttendeesField = document.getElementById('max_attendees_field');
    
    if (meetingTypeSelect) {
        meetingTypeSelect.addEventListener('change', function() {
            if (this.value === 'group') {
                maxAttendeesField.style.display = 'block';
            } else {
                maxAttendeesField.style.display = 'none';
            }
        });
        
        // Trigger on load
        meetingTypeSelect.dispatchEvent(new Event('change'));
    }

    // Handle availability checkboxes
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const row = this.closest('tr');
            const timeInputs = row.querySelectorAll('.time-input');
            
            timeInputs.forEach(input => {
                input.disabled = !this.checked;
            });
        });
    });

    // Handle brand name input
    const brandNameInput = document.querySelector('input[name="brand_name"]');
    if (brandNameInput) {
        brandNameInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
        });
    }

    // Handle form submission
    stepper.on('kt.stepper.next', function (stepper) {
        stepper.goNext();
    });

    stepper.on('kt.stepper.previous', function (stepper) {
        stepper.goPrevious();
    });

    stepper.on('kt.stepper.submit', function (stepper) {
        const form = document.getElementById('onboarding_form');
        const submitBtn = stepper.getElement().querySelector('[data-kt-stepper-action="submit"]');
        
        submitBtn.setAttribute('data-kt-indicator', 'on');
        submitBtn.disabled = true;
        
        form.submit();
    });

    // Handle view profile button
    document.getElementById('view-profile-btn')?.addEventListener('click', function() {
        const brandName = document.querySelector('input[name="brand_name"]').value;
        if (brandName) {
            window.open(`{{ url('/') }}/${brandName}`, '_blank');
        }
    });
});
</script>
@endpush
@endsection