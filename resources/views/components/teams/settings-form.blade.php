@props(['team'])

<div id="team-settings-wrapper">
    <!-- Success Message Container -->
    <div id="settings-status-container" class="mb-5" style="display: none;">
        <div id="settings-success" class="alert alert-success d-flex align-items-center p-5" style="display: none;">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Team settings updated successfully</h4>
                <span>Your resource sharing configuration has been saved.</span>
            </div>
        </div>
    </div>

    <form action="{{ route('teams.update', $team) }}" method="POST" id="team-settings-form" class="team-settings-form" novalidate data-fv-form="false">
        @csrf
        @method('PUT')
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_sms_credits" id="share_sms_credits" 
                      value="1" {{ $team->share_sms_credits ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_sms_credits">
                    Share SMS Credits
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to use the owner's SMS credits</div>
        </div>
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_contacts" id="share_contacts" 
                      value="1" {{ $team->share_contacts ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_contacts">
                    Share Contacts
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to access and use the team's contact lists</div>
        </div>
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_sender_names" id="share_sender_names" 
                      value="1" {{ $team->share_sender_names ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_sender_names">
                    Share Sender Names
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to use the team's approved sender names</div>
        </div>
        
        <div class="mb-5">
            <div class="form-check form-switch form-check-custom form-check-solid mb-3">
                <input class="form-check-input" type="checkbox" name="share_contact_groups" id="share_contact_groups" 
                      value="1" {{ $team->share_contact_groups ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold text-gray-800" for="share_contact_groups">
                    Share Contact Groups
                </label>
            </div>
            <div class="text-muted fs-7 ms-9">Allow team members to access and use the team's contact groups</div>
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
    console.log('🚀 Team settings initialization started');
    
    // Immediately hide any session flash messages to prevent conflicts
    const hideSessionMessages = () => {
        const sessionElements = document.querySelectorAll('.alert-success, .alert-danger, .alert-warning, .alert-info, [id*="session"], [class*="session"]');
        sessionElements.forEach(el => {
            if (!el.closest('#team-settings-wrapper')) {
                el.style.display = 'none';
                el.style.visibility = 'hidden';
                el.style.opacity = '0';
            }
        });
    };
    
    hideSessionMessages();
    
    const form = document.getElementById('team-settings-form');
    if (!form) {
        console.error('❌ Team settings form not found');
        return;
    }
    
    // Disable FormValidation completely for this form
    form.setAttribute('data-fv-excluded', 'true');
    form.classList.add('fv-excluded');
    
    const switches = form.querySelectorAll('.form-check-input');
    const submitButton = document.getElementById('settings-submit-button');
    const statusContainer = document.getElementById('settings-status-container');
    const successAlert = document.getElementById('settings-success');
    
    if (!switches.length || !submitButton) {
        console.error('❌ Required elements not found');
        return;
    }
    
    console.log(`✅ Found ${switches.length} switches and submit button`);
    
    let originalStates = {};
    let isSubmitting = false;

    // Store original states
    switches.forEach(switchInput => {
        originalStates[switchInput.id] = switchInput.checked;
    });
    
    console.log('📝 Original states:', originalStates);

    // Function to check if there are changes
    const checkForChanges = () => {
        console.log('🔍 === CHECKING FOR CHANGES ===');
        console.log('📝 Original states:', originalStates);
        
        const currentStates = {};
        const changeDetails = [];
        let hasChanges = false;
        
        Array.from(switches).forEach(s => {
            currentStates[s.id] = s.checked;
            const isChanged = s.checked !== originalStates[s.id];
            
            if (isChanged) {
                hasChanges = true;
                changeDetails.push(`${s.id}: ${originalStates[s.id]} → ${s.checked}`);
            }
            
            console.log(`📊 ${s.id}: current=${s.checked}, original=${originalStates[s.id]}, changed=${isChanged}`);
        });
        
        console.log('📝 Current states:', currentStates);
        console.log('🔄 Changes detected:', changeDetails);
        console.log('📊 Overall has changes:', hasChanges);
        console.log('📊 Button disabled before update:', submitButton.disabled);
        
        submitButton.disabled = !hasChanges;
        
        if (hasChanges) {
            submitButton.classList.add('btn-active');
            console.log('✅ Button ENABLED (changes detected)');
        } else {
            submitButton.classList.remove('btn-active');
            console.log('🔒 Button DISABLED (no changes)');
        }
        
        console.log('📊 Button disabled after update:', submitButton.disabled);
        console.log('🔍 === END CHANGE CHECK ===');
        
        return hasChanges;
    };

    // Add change listeners
    switches.forEach(switchInput => {
        switchInput.addEventListener('change', function() {
            console.log('🔄 =============== SWITCH CHANGE EVENT ===============');
            console.log(`🔄 Switch "${this.id}" changed from ${originalStates[this.id]} to ${this.checked}`);
            console.log('🔄 Event target:', this);
            console.log('🔄 Current checked state:', this.checked);
            console.log('🔄 Original state for this switch:', originalStates[this.id]);
            console.log('🔄 Change detected for this switch:', this.checked !== originalStates[this.id]);
            
            const hasChanges = checkForChanges();
            console.log(`🔄 Overall result: hasChanges=${hasChanges}, buttonDisabled=${submitButton.disabled}`);
            
            // Hide status messages when making changes
            statusContainer.style.display = 'none';
            hideSessionMessages();
            
            console.log('🔄 =============== END SWITCH CHANGE EVENT ===============');
        });
    });
    
    // Initial check
    checkForChanges();

    // Handle form submission with clean event handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🚀 Form submission started');
        console.log('📝 Current form values:', Object.fromEntries(
            Array.from(switches).map(s => [s.id, s.checked])
        ));
        console.log('📝 Original states:', originalStates);
        
        if (isSubmitting) {
            console.log('⚠️ Already submitting, blocking duplicate');
            return false;
        }
        
        isSubmitting = true;
        
        // Disable form during submission
        switches.forEach(s => s.disabled = true);
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        
        // Hide previous messages
        statusContainer.style.display = 'none';
        hideSessionMessages();
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('_method', 'PUT');
        
        // Store what we're about to submit (this will become the new "original" if successful)
        const submittedValues = {};
        switches.forEach(switchInput => {
            const value = switchInput.checked ? '1' : '0';
            formData.append(switchInput.name, value);
            submittedValues[switchInput.id] = switchInput.checked;
            console.log(`📋 Submitting ${switchInput.name}: ${value}`);
        });
        
        console.log('📤 Submitted values:', submittedValues);
        console.log('🌐 Sending AJAX request...');
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log(`📡 Response: ${response.status} ${response.statusText}`);
            
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || `HTTP ${response.status}`);
                }).catch(() => {
                    throw new Error(`HTTP ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Success response:', data);
            
            if (data.success) {
                // Show success message
                statusContainer.style.display = 'block';
                successAlert.style.display = 'flex';
                
                // IMPORTANT: Update original states to match what was successfully saved
                // This is the key fix - use the submitted values that were confirmed by server
                console.log('🎉 Settings saved successfully');
                console.log('📝 Old original states:', originalStates);
                
                Object.keys(submittedValues).forEach(switchId => {
                    originalStates[switchId] = submittedValues[switchId];
                });
                
                console.log('📝 New original states (from successful save):', originalStates);
                
            } else {
                throw new Error(data.message || 'Update failed');
            }
        })
        .catch(error => {
            console.error('❌ Save failed:', error.message);
            console.log('❌ Original states unchanged due to save failure');
            // DON'T update original states on error - keep them as they were
        })
        .finally(() => {
            console.log('🏁 Cleaning up after submission');
            
            // Re-enable form
            switches.forEach(s => s.disabled = false);
            
            // Check for changes and update button state 
            const hasChanges = checkForChanges();
            console.log('📊 Changes detected after cleanup:', hasChanges);
            console.log('📝 Current form values after cleanup:', Object.fromEntries(
                Array.from(switches).map(s => [s.id, s.checked])
            ));
            console.log('📝 Original states for comparison:', originalStates);
            
            submitButton.innerHTML = '<i class="ki-outline ki-check fs-2 me-2"></i>Save Changes';
            
            // Reset submission flag
            isSubmitting = false;
            
            console.log('✅ Form ready for next submission');
        });
        
        return false;
    }, true); // Use capture phase to prevent other handlers
    
    console.log('✅ Team settings form initialized successfully');
});
</script>
@endpush