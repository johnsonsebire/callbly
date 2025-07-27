@extends('layouts.master')

@section('title', 'Edit Team')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-8 col-lg-6 mx-auto">
                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Edit Team</h3>
                                <div class="card-toolbar">
                                    <a href="{{ route('teams.show', $team) }}" class="btn btn-light">
                                        <i class="ki-outline ki-arrow-left fs-2 me-1"></i>Back to Team
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success d-flex align-items-center p-5 mb-4">
                                        <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-success">Success!</h4>
                                            <span>{{ session('success') }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger d-flex align-items-center p-5 mb-4">
                                        <i class="ki-duotone ki-cross-circle fs-2hx text-danger me-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <h4 class="mb-1 text-danger">Error!</h4>
                                            <span>{{ session('error') }}</span>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('teams.update', $team) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-5">
                                        <label for="name" class="form-label fw-semibold">Team Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $team->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-5">
                                        <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="4">{{ old('description', $team->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Provide a brief description of your team and its purpose.</div>
                                    </div>

                                    <div class="separator separator-dashed my-8"></div>
                                    
                                    <h4 class="fs-4 fw-semibold mb-3">Resource Sharing Settings</h4>
                                    <p class="text-muted fs-6 mb-6">Configure what resources team members can access and use.</p>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <!-- Hidden input to ensure we always get a value for this field -->
                                            <input type="hidden" name="share_sms_credits" value="0">
                                            <input class="form-check-input @error('share_sms_credits') is-invalid @enderror" 
                                                   type="checkbox" name="share_sms_credits" id="share_sms_credits" value="1"
                                                   {{ old('share_sms_credits', $team->share_sms_credits) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_sms_credits">
                                                Share SMS Credits
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to use the owner's SMS credits for sending messages</div>
                                        @error('share_sms_credits')
                                            <div class="invalid-feedback d-block ms-9">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <!-- Hidden input to ensure we always get a value for this field -->
                                            <input type="hidden" name="share_contacts" value="0">
                                            <input class="form-check-input @error('share_contacts') is-invalid @enderror" 
                                                   type="checkbox" name="share_contacts" id="share_contacts" value="1"
                                                   {{ old('share_contacts', $team->share_contacts) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_contacts">
                                                Share Contacts
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to access and use the team's contact lists</div>
                                        @error('share_contacts')
                                            <div class="invalid-feedback d-block ms-9">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <!-- Hidden input to ensure we always get a value for this field -->
                                            <input type="hidden" name="share_sender_names" value="0">
                                            <input class="form-check-input @error('share_sender_names') is-invalid @enderror" 
                                                   type="checkbox" name="share_sender_names" id="share_sender_names" value="1"
                                                   {{ old('share_sender_names', $team->share_sender_names) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_sender_names">
                                                Share Sender Names
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to use the team's approved sender names</div>
                                        @error('share_sender_names')
                                            <div class="invalid-feedback d-block ms-9">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-5">
                                        <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                                            <!-- Hidden input to ensure we always get a value for this field -->
                                            <input type="hidden" name="share_contact_groups" value="0">
                                            <input class="form-check-input @error('share_contact_groups') is-invalid @enderror" 
                                                   type="checkbox" name="share_contact_groups" id="share_contact_groups" value="1"
                                                   {{ old('share_contact_groups', $team->share_contact_groups) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-gray-800" for="share_contact_groups">
                                                Share Contact Groups
                                            </label>
                                        </div>
                                        <div class="text-muted fs-7 ms-9">Allow team members to access and use the team's contact groups</div>
                                        @error('share_contact_groups')
                                            <div class="invalid-feedback d-block ms-9">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="separator separator-dashed my-8"></div>

                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('teams.show', $team) }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary" id="save-team-btn">
                                            <i class="ki-outline ki-check fs-2 me-1"></i>Save Changes
                                        </button>
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
    const form = document.querySelector('form');
    const saveButton = document.getElementById('save-team-btn');
    const inputs = form.querySelectorAll('input[type="text"], textarea, input[type="checkbox"]');
    
    // Auto-hide success/error alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');
    alerts.forEach(alert => {
        if (alert.textContent.includes('Success!') || alert.textContent.includes('Error!')) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            }, 5000); // Hide after 5 seconds
        }
    });
    
    // Store original values to detect changes
    const originalValues = {};
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            originalValues[input.name] = input.checked;
        } else {
            originalValues[input.name] = input.value;
        }
    });
    
    // Function to check if form has changes
    function checkForChanges() {
        let hasChanges = false;
        inputs.forEach(input => {
            let currentValue;
            if (input.type === 'checkbox') {
                currentValue = input.checked;
            } else {
                currentValue = input.value;
            }
            
            if (currentValue !== originalValues[input.name]) {
                hasChanges = true;
            }
        });
        
        // Update save button appearance based on changes
        if (hasChanges) {
            saveButton.classList.remove('btn-primary');
            saveButton.classList.add('btn-success');
            saveButton.innerHTML = '<i class="ki-outline ki-check fs-2 me-1"></i>Save Changes';
        } else {
            saveButton.classList.remove('btn-success');
            saveButton.classList.add('btn-primary');
            saveButton.innerHTML = '<i class="ki-outline ki-check fs-2 me-1"></i>Save Changes';
        }
    }
    
    // Add change listeners to all form inputs
    inputs.forEach(input => {
        input.addEventListener('change', checkForChanges);
        if (input.type === 'text' || input.tagName === 'TEXTAREA') {
            input.addEventListener('input', checkForChanges);
        }
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        // Show loading state
        saveButton.disabled = true;
        saveButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        
        // Disable all form inputs during submission
        inputs.forEach(input => input.disabled = true);
    });
    
    // Check initial state
    checkForChanges();
});
</script>
@endpush

@endsection