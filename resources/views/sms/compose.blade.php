@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container mt-4">
    <x-card>
        <x-slot:header>
            <div class="card-title">
                <h5 class="mb-0">Compose New SMS</h5>
            </div>
        </x-slot:header>
        <div class="row">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('sms.send') }}" id="smsForm">
                    @csrf
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="mb-3">
                        <label for="sender_id" class="form-label">Sender ID</label>
                        <select name="sender_id" id="sender_id" class="form-select @error('sender_id') is-invalid @enderror" required>
                            <option value="">Select a sender ID</option>
                            @foreach($senderNames as $senderName)
                                <option value="{{ $senderName->name }}" {{ old('sender_id') == $senderName->name ? 'selected' : '' }}>
                                    {{ $senderName->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sender_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($senderNames->isEmpty())
                            <div class="form-text text-danger">
                                You need to <a href="{{ route('sms.sender-names') }}">register a sender ID</a> before sending SMS.
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="message" class="form-label mb-0">Message</label>
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#templateModal">Use Template</button>
                        </div>
                        <textarea name="message" id="message" rows="6" class="form-control @error('message') is-invalid @enderror" required>{{ request('template_content') ?: old('message') }}</textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small id="characterCount" class="text-muted">0 characters</small>
                            <small id="messageCount" class="text-muted">0 message(s)</small>
                        </div>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <x-card title="Recipients" icon="fas fa-users">
                        <ul class="nav nav-tabs mb-3" id="recipientsTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">Enter Numbers</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab" aria-controls="groups" aria-selected="false">Select Groups</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="all-contacts-tab" data-bs-toggle="tab" data-bs-target="#all-contacts" type="button" role="tab" aria-controls="all-contacts" aria-selected="false">All Contacts</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="recipientsTabContent">
                            <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                                <textarea name="recipients" id="recipients" rows="6" class="form-control @error('recipients') is-invalid @enderror" placeholder="Enter phone numbers separated by commas, new lines, or spaces">{{ old('recipients') }}</textarea>
                                <div class="form-text">Example: 233244123456, 233244123457 (without + sign)</div>
                                <div id="recipientCount" class="mt-1 fw-bold">0 recipient(s)</div>
                                @error('recipients')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
                                @if($contactGroups->count() > 0)
                                    <div class="mb-3">
                                        <div class="list-group">
                                            @foreach($contactGroups as $group)
                                                <label class="list-group-item">
                                                    <input class="form-check-input me-1 contact-group-checkbox" type="checkbox" name="contact_group_ids[]" value="{{ $group->id }}" data-contacts="{{ $group->contacts_count }}">
                                                    {{ $group->name }} <span class="badge bg-secondary">{{ $group->contacts_count }} contacts</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="mt-2 fw-bold" id="groupRecipientsCount">0 recipient(s) selected</div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        You don't have any contact groups yet. <a href="{{ route('contact-groups.create') }}">Create a contact group</a> first.
                                    </div>
                                @endif
                            </div>
                            <div class="tab-pane fade" id="all-contacts" role="tabpanel" aria-labelledby="all-contacts-tab">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" name="send_to_all_contacts" id="sendToAllContacts">
                                    <label class="form-check-label" for="sendToAllContacts">
                                        Send to all my contacts
                                    </label>
                                </div>
                                <div class="alert alert-warning mt-2">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    This will send the message to all contacts in your address book ({{ $totalContactsCount ?? 0 }} contacts).
                                </div>
                            </div>
                        </div>
                    </x-card>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="sendButton" {{ $senderNames->isEmpty() ? 'disabled' : '' }}>
                            Send Message
                        </button>
                    </div>
                </form>

                <!-- Template Modal -->
                <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="templateModalLabel">Select Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        @if($templates->count())
                           <ul class="list-group">
                             @foreach($templates as $template)
                               <li class="list-group-item d-flex justify-content-between align-items-start">
                                 <div>
                                   <div class="fw-bold">{{ $template->name }}</div>
                                   <p>{{ Str::limit($template->content, 100) }}</p>
                                 </div>
                                 <button type="button" class="btn btn-sm btn-primary use-template-btn" data-id="{{ $template->id }}" data-bs-dismiss="modal">Use</button>
                               </li>
                             @endforeach
                           </ul>
                        @else
                           <p>No templates available. <a href="{{ route('sms.templates') }}">Create one</a>.</p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

            </div>
            <div class="col-lg-4">
                <x-card title="Message Summary" icon="fas fa-chart-bar" headerClass="bg-primary text-white" class="my-5">
                    <div class="d-flex justify-content-between mb-3">
                        <div><strong>Credits Required:</strong></div>
                        <div id="creditsNeeded" class="badge bg-primary">0</div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div><strong>Recipients:</strong></div>
                        <div id="recipientsCount" class="badge bg-secondary">0</div>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div><strong>Characters:</strong></div>
                        <div><span id="charsCount">0</span>/160</div>
                    </div>
                    <div class="alert alert-info mt-2 mb-0" id="messageInfo" style="display: none;">
                        <p class="mb-0"><small><i class="bi bi-info-circle me-1"></i> This message will be sent as <span id="messageParts" class="fw-bold">0</span> part(s).</small></p>
                    </div>
                </x-card>

                <x-card title="Tips" icon="fas fa-lightbulb">
                    <ul class="mb-0">
                        <li>Keep your message concise to reduce costs.</li>
                        <li>Avoid special characters that might increase message parts.</li>
                        <li>Include country code for all numbers (e.g. 233).</li>
                        <li>Test with a small group first.</li>
                    </ul>
                </x-card>
            </div>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define all required elements
        const messageField = document.getElementById('message');
        const recipientsField = document.getElementById('recipients');
        const characterCount = document.getElementById('characterCount');
        const messageCount = document.getElementById('messageCount');
        const recipientCount = document.getElementById('recipientCount');
        const charsCount = document.getElementById('charsCount');
        const recipientsCount = document.getElementById('recipientsCount');
        const creditsNeeded = document.getElementById('creditsNeeded');
        const messageParts = document.getElementById('messageParts');
        const messageInfo = document.getElementById('messageInfo');
        const groupCheckboxes = document.querySelectorAll('.contact-group-checkbox');
        const sendToAllContactsCheckbox = document.getElementById('sendToAllContacts');
        const groupRecipientsCount = document.getElementById('groupRecipientsCount');
        
        let activeTab = 'manual'; // Track which tab is active

        // Tab handling
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                activeTab = e.target.getAttribute('aria-controls');
                updateRecipientCounts();
            });
        });
        
        // Handle initial URL params for template
        const urlParams = new URLSearchParams(window.location.search);
        const templateId = urlParams.get('template');
        if (templateId) {
            loadTemplateContent(templateId);
        }
        
        // Character counter - ensure this fires immediately on page load and when typing
        messageField.addEventListener('input', updateMessageMetrics);
        messageField.addEventListener('keyup', updateMessageMetrics);
        messageField.addEventListener('change', updateMessageMetrics);
        
        // Recipient counter - ensure multiple events are captured
        recipientsField.addEventListener('input', updateRecipientCounts);
        recipientsField.addEventListener('keyup', updateRecipientCounts);
        recipientsField.addEventListener('change', updateRecipientCounts);
        
        // Contact group selection
        groupCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateRecipientCounts);
        });
        
        // All contacts checkbox
        if (sendToAllContactsCheckbox) {
            sendToAllContactsCheckbox.addEventListener('change', updateRecipientCounts);
        }

        // Initialize metrics on page load - critical to show initial values
        updateMessageMetrics();
        updateRecipientCounts();
        
        // Template selection
        document.querySelectorAll('.use-template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const templateId = this.getAttribute('data-id');
                loadTemplateContent(templateId);
            });
        });

        function loadTemplateContent(templateId) {
            // Show loading state
            messageField.disabled = true;
            messageField.value = 'Loading template...';
            
            // Fetch template content from server
            fetch(`{{ url('sms/templates') }}/${templateId}/content`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        messageField.value = data.content;
                        // Explicitly trigger metric updates after loading template
                        updateMessageMetrics();
                    } else {
                        alert('Failed to load template: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error loading template:', error);
                    alert('Error loading template. Please try again.');
                })
                .finally(() => {
                    messageField.disabled = false;
                });
        }

        function updateMessageMetrics() {
            // Get the current length of the message
            const length = messageField.value.length;
            
            // Update character count displays
            characterCount.textContent = length + ' characters';
            charsCount.textContent = length;
            
            // Calculate message parts (160 chars for single SMS, 153 for multi-part)
            let parts = 1;
            if (length <= 160) {
                parts = 1;
            } else {
                parts = Math.ceil((length - 160) / 153) + 1;
            }
            
            // Update message parts display
            messageCount.textContent = parts + ' message(s)';
            messageParts.textContent = parts;
            
            // Show message parts info if more than one part
            if (parts > 1) {
                messageInfo.style.display = 'block';
            } else {
                messageInfo.style.display = 'none';
            }
            
            // Calculate credits
            calculateCredits();
            
            // Debug output to console to verify function execution
            console.log(`Message metrics updated: ${length} chars, ${parts} parts`);
        }
        
        function updateRecipientCounts() {
            let recipientCount = 0;
            
            // Count based on active tab
            if (activeTab === 'manual') {
                recipientCount = getRecipientCount(recipientsField.value);
                document.getElementById('recipientCount').textContent = recipientCount + ' recipient(s)';
            }
            else if (activeTab === 'groups') {
                recipientCount = 0;
                const selectedGroups = document.querySelectorAll('.contact-group-checkbox:checked');
                selectedGroups.forEach(group => {
                    recipientCount += parseInt(group.getAttribute('data-contacts') || 0);
                });
                groupRecipientsCount.textContent = recipientCount + ' recipient(s) selected';
            }
            else if (activeTab === 'all-contacts') {
                recipientCount = sendToAllContactsCheckbox.checked ? {{ $totalContactsCount ?? 0 }} : 0;
            }
            
            // Update the global count
            recipientsCount.textContent = recipientCount;
            
            // Calculate credits
            calculateCredits();
            
            // Debug output
            console.log(`Recipient count updated: ${recipientCount} recipients`);
        }
        
        // Count recipients in manual input
        function getRecipientCount(text) {
            if (!text.trim()) return 0;
            // Split by commas, newlines, or spaces and filter empty entries
            const recipients = text.split(/[\s,\n]+/).filter(item => item.trim() !== '');
            return recipients.length;
        }
        
        // Calculate credits needed with both frontend and backend validation
        function calculateCredits() {
            const message = messageField.value;
            const recipientCount = parseInt(recipientsCount.textContent) || 0;
            
            // Immediate feedback with frontend calculation
            let parts = 1;
            const length = message.length;
            if (length <= 160) {
                parts = 1;
            } else {
                parts = Math.ceil((length - 160) / 153) + 1;
            }
            
            const estimatedCredits = parts * recipientCount;
            creditsNeeded.textContent = estimatedCredits;
            
            // Only call the server if we have actual data to calculate
            if (message && recipientCount > 0) {
                // Debounce API calls
                clearTimeout(window.calculateTimeout);
                window.calculateTimeout = setTimeout(function() {
                    const formData = new FormData();
                    formData.append('message', message);
                    
                    // Get recipients based on active tab
                    if (activeTab === 'manual') {
                        formData.append('recipients', recipientsField.value);
                    } else {
                        // For groups or all contacts, use dummy recipients for calculation
                        formData.append('recipients', '1'.repeat(recipientCount).split('').join(','));
                    }
                    
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    fetch('{{ route('sms.calculate-credits') }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        creditsNeeded.textContent = data.credits_needed;
                        messageParts.textContent = data.sms_parts;
                        
                        if (data.sms_parts > 1) {
                            messageInfo.style.display = 'block';
                        }
                        
                        console.log('API credits calculation:', data);
                    })
                    .catch(error => {
                        console.error('Error calculating credits:', error);
                    });
                }, 300);
            }
        }
    });
</script>
@endpush