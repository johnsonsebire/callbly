@props(['team'])

<form action="{{ route('teams.update', $team) }}" method="POST" id="team-settings-form">
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
        <button type="submit" class="btn btn-primary" disabled>
            <i class="ki-outline ki-check fs-2 me-2"></i>Save Settings
        </button>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('team-settings-form');
    const switches = form.querySelectorAll('.form-check-input');
    const submitButton = form.querySelector('button[type="submit"]');
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
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Show success message
            toastr.success('Team settings updated successfully');
            
            // Update original states
            switches.forEach(switchInput => {
                originalStates[switchInput.id] = switchInput.checked;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error message
            toastr.error('Failed to update team settings');
            
            // Restore original states on error
            switches.forEach(switchInput => {
                switchInput.checked = originalStates[switchInput.id];
            });
        })
        .finally(() => {
            // Re-enable form elements
            switches.forEach(s => s.disabled = false);
            submitButton.disabled = true; // Disable until next change
            submitButton.classList.remove('btn-active');
            submitButton.innerHTML = '<i class="ki-outline ki-check fs-2 me-2"></i>Save Settings';
        });
    });
});
</script>
@endpush