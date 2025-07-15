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
    
    function toggleFields() {
        const isEnabled = enableCheckbox.checked;
        amountField.disabled = !isEnabled;
        emailCheckbox.disabled = !isEnabled;
        
        if (!isEnabled) {
            amountField.value = 0;
            emailCheckbox.checked = false;
        }
    }
    
    enableCheckbox.addEventListener('change', toggleFields);
    toggleFields(); // Initial state
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
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
    });
});
</script>
@endsection
