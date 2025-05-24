@props(['team'])

<div id="team-settings-wrapper">
    <!-- Status Messages Container (Hidden by Default) -->
    <div id="settings-status-container" class="mb-5" style="display: none;">
        <div id="settings-success" class="alert alert-success d-flex align-items-center p-5" style="display: none;">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Team settings updated successfully</h4>
                <span>Your resource sharing configuration has been saved.</span>
            </div>
        </div>
        
        <div id="settings-error" class="alert alert-danger d-flex align-items-center p-5" style="display: none;">
            <i class="ki-duotone ki-shield-cross fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">Failed to update team settings</h4>
                <span id="error-message">An error occurred while saving your changes. Please try again.</span>
            </div>
        </div>
    </div>

    <form action="{{ route('teams.update', $team) }}" method="POST" id="team-settings-form" class="team-settings-form">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_sms_credits" id="share_sms_credits" 
                      {{ $team->share_sms_credits ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_sms_credits">
                    Share SMS Credits
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to use the owner's SMS credits</div>
        </div>
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_contacts" id="share_contacts" 
                      {{ $team->share_contacts ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_contacts">
                    Share Contacts
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to access and use the team's contact lists</div>
        </div>
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_sender_names" id="share_sender_names" 
                      {{ $team->share_sender_names ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_sender_names">
                    Share Sender Names
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to use the team's approved sender names</div>
        </div>
        
        <div class="text-end">
            <button type="submit" class="btn btn-primary" disabled id="settings-submit-button">
                <i class="ki-outline ki-check fs-2 me-2"></i>Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide any existing alerts from the server
    document.querySelectorAll('.alert:not(#settings-success):not(#settings-error)').forEach(alert => {
        alert.style.display = 'none';
    });

    const form = document.getElementById('team-settings-form');
    if (!form) return; // Exit if form doesn't exist
    
    const switches = form.querySelectorAll('.form-check-input');
    const submitButton = document.getElementById('settings-submit-button');
    const statusContainer = document.getElementById('settings-status-container');
    const successAlert = document.getElementById('settings-success');
    const errorAlert = document.getElementById('settings-error');
    const errorMessage = document.getElementById('error-message');
    let originalStates = {};

    // Store original states
    switches.forEach(switchInput => {
        originalStates[switchInput.id] = switchInput.checked;
    });

    // Enable save button only when changes are made
    switches.forEach(switchInput => {
        switchInput.addEventListener('change', function() {
            const hasChanges = Array.from(switches).some(s => s.checked !== originalStates[s.id]);
            submitButton.disabled = !hasChanges;
            
            // Visual feedback for changes
            if (hasChanges) {
                submitButton.classList.add('btn-active');
            } else {
                submitButton.classList.remove('btn-active');
            }
            
            // Hide status messages when making new changes
            statusContainer.style.display = 'none';
        });
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        // Add unchecked switches as false values
        switches.forEach(switchInput => {
            if (!formData.has(switchInput.name)) {
                formData.append(switchInput.name, '0');
            }
        });
        
        // Disable form elements during submission
        switches.forEach(s => s.disabled = true);
        submitButton.disabled = true;
        
        // Show loading state
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        
        // Hide any previous status messages
        statusContainer.style.display = 'none';
        successAlert.style.display = 'none';
        errorAlert.style.display = 'none';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to update team settings');
                });
            }
            return response.json();
        })
        .then(data => {
            // Show success message
            statusContainer.style.display = 'block';
            successAlert.style.display = 'flex';
            
            // Update original states
            switches.forEach(switchInput => {
                originalStates[switchInput.id] = switchInput.checked;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error message
            statusContainer.style.display = 'block';
            errorAlert.style.display = 'flex';
            errorMessage.textContent = error.message || 'An error occurred while saving your changes. Please try again.';
        })
        .finally(() => {
            // Re-enable form elements
            switches.forEach(s => s.disabled = false);
            submitButton.disabled = true; // Disable until next change
            submitButton.classList.remove('btn-active');
            submitButton.innerHTML = '<i class="ki-outline ki-check fs-2 me-2"></i>Save Changes';
        });
    });
});
</script>
@endpush