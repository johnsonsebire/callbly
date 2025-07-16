@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <div class="card-title">
                            <h2 class="fw-bold">System Settings</h2>
                            <span class="text-muted pt-1 fw-semibold fs-6">Manage global system configurations</span>
                        </div>
                        <div class="card-toolbar">
                            <form method="POST" action="{{ route('admin.settings.reset') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-light-warning" 
                                        onclick="return confirm('Are you sure you want to reset all settings to default values?')">
                                    <i class="ki-duotone ki-arrows-circle fs-2"></i>Reset to Defaults
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body py-3">
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

                        @if($errors->any())
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">Validation Errors</h4>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-2 text-danger"></i>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Free SMS Credits Settings -->
                                <div class="col-md-6">
                                    <div class="card card-custom gutter-b">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-label">Free SMS Credits</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Enable Free Credits -->
                                            <div class="form-group mb-5">
                                                <label class="form-label">Enable Free Credits for New Users</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="new_user_free_sms_credits_enabled" name="new_user_free_sms_credits_enabled" 
                                                           value="1" {{ $settings['new_user_free_sms_credits_enabled'] ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="new_user_free_sms_credits_enabled">
                                                        Give new users free SMS credits upon registration
                                                    </label>
                                                </div>
                                                <div class="form-text">When enabled, new users will automatically receive free SMS credits</div>
                                            </div>

                                            <!-- Free Credits Amount -->
                                            <div class="form-group mb-5">
                                                <label for="new_user_free_sms_credits_amount" class="form-label">Free Credits Amount</label>
                                                <input type="number" class="form-control form-control-solid" 
                                                       id="new_user_free_sms_credits_amount" name="new_user_free_sms_credits_amount" 
                                                       value="{{ old('new_user_free_sms_credits_amount', $settings['new_user_free_sms_credits_amount']) }}" 
                                                       min="0" max="100" required>
                                                <div class="form-text">Number of free SMS credits to give new users (0-100)</div>
                                            </div>

                                            <!-- Welcome Email -->
                                            <div class="form-group mb-5">
                                                <label class="form-label">Welcome Email</label>
                                                <div class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="welcome_email_enabled" name="welcome_email_enabled" 
                                                           value="1" {{ $settings['welcome_email_enabled'] ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="welcome_email_enabled">
                                                        Send welcome email notification
                                                    </label>
                                                </div>
                                                <div class="form-text">Send email notification when free credits are awarded</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- System Configuration -->
                                <div class="col-md-6">
                                    <div class="card card-custom gutter-b">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-label">System Configuration</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- System Sender Name -->
                                            <div class="form-group mb-5">
                                                <label for="system_sender_name" class="form-label">System Sender Name</label>
                                                <input type="text" class="form-control form-control-solid" 
                                                       id="system_sender_name" name="system_sender_name" 
                                                       value="{{ old('system_sender_name', $settings['system_sender_name']) }}" 
                                                       maxlength="11" pattern="[a-zA-Z0-9]+" required>
                                                <div class="form-text">
                                                    Sender name for system messages (max 11 characters, alphanumeric only)
                                                </div>
                                            </div>

                                            <!-- Current Settings Display -->
                                            <div class="separator separator-dashed my-5"></div>
                                            <div class="d-flex flex-column">
                                                <h5 class="mb-3">Current Status</h5>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="bullet bullet-dot me-3 {{ $settings['new_user_free_sms_credits_enabled'] ? 'bg-success' : 'bg-danger' }}"></span>
                                                    <span class="fw-semibold text-gray-600">Free Credits: 
                                                        <span class="text-gray-800">{{ $settings['new_user_free_sms_credits_enabled'] ? 'Enabled' : 'Disabled' }}</span>
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="bullet bullet-dot me-3 bg-primary"></span>
                                                    <span class="fw-semibold text-gray-600">Credits Amount: 
                                                        <span class="text-gray-800">{{ $settings['new_user_free_sms_credits_amount'] }}</span>
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="bullet bullet-dot me-3 {{ $settings['welcome_email_enabled'] ? 'bg-success' : 'bg-danger' }}"></span>
                                                    <span class="fw-semibold text-gray-600">Welcome Email: 
                                                        <span class="text-gray-800">{{ $settings['welcome_email_enabled'] ? 'Enabled' : 'Disabled' }}</span>
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="bullet bullet-dot me-3 bg-info"></span>
                                                    <span class="fw-semibold text-gray-600">Sender Name: 
                                                        <span class="text-gray-800">{{ $settings['system_sender_name'] }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sender Name Whitelist Settings -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="card card-custom gutter-b">
                                        <div class="card-header">
                                            <div class="card-title">
                                                <h3 class="card-label">Sender Name Whitelist Automation</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <!-- Auto Send Enable -->
                                                    <div class="form-group mb-5">
                                                        <label class="form-label">Auto-Send Whitelist Requests</label>
                                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   id="sender_name_auto_send_enabled" name="sender_name_auto_send_enabled" 
                                                                   value="1" {{ $settings['sender_name_auto_send_enabled'] ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="sender_name_auto_send_enabled">
                                                                Automatically send whitelist request documents via email
                                                            </label>
                                                        </div>
                                                        <!-- Hidden input for unchecked state -->
                                                        <input type="hidden" name="sender_name_auto_send_enabled" value="0">
                                                        <div class="form-text">When enabled, PDF documents will be automatically emailed to specified addresses when new sender names are requested</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <!-- Current Auto-Send Status -->
                                                    <div class="d-flex flex-column">
                                                        <h5 class="mb-3">Current Auto-Send Status</h5>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="bullet bullet-dot me-3 {{ $settings['sender_name_auto_send_enabled'] ? 'bg-success' : 'bg-danger' }}"></span>
                                                            <span class="fw-semibold text-gray-600">Auto-Send: 
                                                                <span class="text-gray-800">{{ $settings['sender_name_auto_send_enabled'] ? 'Enabled' : 'Disabled' }}</span>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="bullet bullet-dot me-3 {{ !empty($settings['sender_name_notification_emails']) ? 'bg-success' : 'bg-warning' }}"></span>
                                                            <span class="fw-semibold text-gray-600">Email Addresses: 
                                                                <span class="text-gray-800">{{ !empty($settings['sender_name_notification_emails']) ? 'Configured' : 'Not Set' }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Notification Email Addresses -->
                                            <div class="form-group mb-5">
                                                <label for="sender_name_notification_emails" class="form-label">Notification Email Addresses</label>
                                                <textarea class="form-control form-control-solid" 
                                                          id="sender_name_notification_emails" name="sender_name_notification_emails" 
                                                          rows="3" placeholder="email1@example.com, email2@example.com">{{ old('sender_name_notification_emails', $settings['sender_name_notification_emails']) }}</textarea>
                                                <div class="form-text">
                                                    Enter email addresses separated by commas. These addresses will receive whitelist request documents when auto-send is enabled.
                                                    <br><strong>Example:</strong> approvals@telco.com, whitelist@provider.com
                                                </div>
                                            </div>

                                            <!-- Instructions -->
                                            <div class="alert alert-info d-flex align-items-center p-5">
                                                <i class="ki-outline ki-information-2 fs-2hx text-info me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <h4 class="mb-1 text-info">How it works</h4>
                                                    <span>
                                                        <strong>When Auto-Send is Enabled:</strong> PDF whitelist request documents are automatically generated and emailed to the specified addresses when users request new sender names.
                                                        <br><strong>When Auto-Send is Disabled:</strong> You can manually download PDF documents from the sender names management page and send them yourself.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-5">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-check fs-2"></i>Update Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle free credits amount field based on enable checkbox
    const enableCheckbox = document.getElementById('new_user_free_sms_credits_enabled');
    const amountField = document.getElementById('new_user_free_sms_credits_amount');
    const emailCheckbox = document.getElementById('welcome_email_enabled');
    
    // Toggle sender name auto-send fields
    const autoSendCheckbox = document.getElementById('sender_name_auto_send_enabled');
    const emailsField = document.getElementById('sender_name_notification_emails');
    
    function toggleFields() {
        const isEnabled = enableCheckbox.checked;
        amountField.disabled = !isEnabled;
        emailCheckbox.disabled = !isEnabled;
        
        if (!isEnabled) {
            amountField.value = 0;
            emailCheckbox.checked = false;
        }
    }
    
    function toggleAutoSendFields() {
        const isAutoSendEnabled = autoSendCheckbox.checked;
        emailsField.disabled = !isAutoSendEnabled;
        
        if (!isAutoSendEnabled) {
            emailsField.value = '';
        }
    }
    
    enableCheckbox.addEventListener('change', toggleFields);
    autoSendCheckbox.addEventListener('change', toggleAutoSendFields);
    toggleFields(); // Initial state
    toggleAutoSendFields(); // Initial state
    
    // Handle checkbox submission properly
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Ensure checkboxes have proper values
        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            // Remove any existing hidden inputs for this checkbox
            const existingHidden = document.querySelector(`input[type="hidden"][name="${checkbox.name}"]`);
            if (existingHidden && existingHidden !== checkbox) {
                existingHidden.remove();
            }
            
            // Add proper hidden input for unchecked state
            if (!checkbox.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = checkbox.name;
                hiddenInput.value = '0';
                form.appendChild(hiddenInput);
            }
        });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {        });
        
        const senderName = document.getElementById('system_sender_name').value;
        const pattern = /^[a-zA-Z0-9]+$/;
        
        if (!pattern.test(senderName)) {
            e.preventDefault();
            alert('Sender name must contain only letters and numbers.');
            return false;
        }
        
        if (senderName.length > 11) {
            e.preventDefault();
            alert('Sender name must not exceed 11 characters.');
            return false;
        }
        
        // Validate email addresses if auto-send is enabled
        if (autoSendCheckbox.checked && emailsField.value.trim()) {
            const emails = emailsField.value.split(',').map(email => email.trim()).filter(email => email);
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            for (let email of emails) {
                if (!emailPattern.test(email)) {
                    e.preventDefault();
                    alert(`Invalid email address: ${email}`);
                    return false;
                }
            }
        }
    });
});
</script>
@endsection
