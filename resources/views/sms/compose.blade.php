@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Compose SMS Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Compose New SMS</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Send messages to your contacts</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <form method="POST" action="{{ route('sms.send') }}" id="smsForm" enctype="multipart/form-data">
                                    @csrf
                                    @if(session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="mb-3">
                                        <label for="sender_name" class="form-label">Sender ID</label>
                                        <select name="sender_name" id="sender_name" class="form-select @error('sender_name') is-invalid @enderror" required>
                                            <option value="">Select a sender ID</option>
                                            @foreach($senderNames as $senderName)
                                                <option value="{{ $senderName->name }}" {{ old('sender_name') == $senderName->name ? 'selected' : '' }}>
                                                    {{ $senderName->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sender_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        @if($senderNames->isEmpty())
                                            <div class="form-text text-danger">
                                                You need to <a href="{{ route('sms.sender-names') }}">register a sender ID</a> before sending SMS.
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="campaign_name" class="form-label">Campaign Name (Optional)</label>
                                        <input type="text" name="campaign_name" id="campaign_name" class="form-control @error('campaign_name') is-invalid @enderror" value="{{ old('campaign_name') }}" placeholder="Campaign name for tracking">
                                        @error('campaign_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

                                    <!-- Hidden field for recipients_type - Will be set via JavaScript based on active tab -->
                                    <input type="hidden" name="recipients_type" id="recipients_type" value="single">

                                    <!-- Recipients Card -->
                                    <div class="card card-flush mb-5">
                                        <div class="card-header pt-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-dark">Recipients</span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-tabs mb-3" id="recipientsTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true" data-recipient-type="multiple">Enter Numbers</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="select-contacts-tab" data-bs-toggle="tab" data-bs-target="#select-contacts" type="button" role="tab" aria-controls="select-contacts" aria-selected="false" data-recipient-type="contacts">Select Contacts</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab" aria-controls="groups" aria-selected="false" data-recipient-type="group">Select Groups</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="all-contacts-tab" data-bs-toggle="tab" data-bs-target="#all-contacts" type="button" role="tab" aria-controls="all-contacts" aria-selected="false" data-recipient-type="all">All Contacts</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-upload" type="button" role="tab" aria-controls="file-upload" aria-selected="false" data-recipient-type="file">Upload File</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="recipientsTabContent">
                                                <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                                                    <div class="mb-3">
                                                        <label for="recipients" class="form-label">Enter Phone Numbers</label>
                                                        <textarea name="recipients" id="recipients" rows="4" class="form-control @error('recipients') is-invalid @enderror" placeholder="Enter one phone number per line or separated by commas">{{ old('recipients') }}</textarea>
                                                        <div class="form-text text-muted">
                                                            <small id="recipientCount">0 recipient(s)</small>
                                                        </div>
                                                        @error('recipients')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="select-contacts" role="tabpanel" aria-labelledby="select-contacts-tab">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <label class="form-label mb-0">Select Contacts</label>
                                                            <div class="input-group input-group-sm w-50">
                                                                <input type="text" id="contactSearchInput" class="form-control" placeholder="Search contacts...">
                                                                <button class="btn btn-outline-secondary" type="button" id="clearContactSearch">
                                                                    <i class="ki-outline ki-cross-circle fs-7"></i>
                                                                </button>
                                                                <button class="btn btn-outline-primary" type="button" id="selectAllContacts">
                                                                    Select All
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
                                                                <div class="list-group list-group-flush">
                                                                    @php 
                                                                        // Get all available contacts (including shared from teams)
                                                                        $availableContacts = auth()->user()->getAvailableContacts()->sortBy('first_name');
                                                                    @endphp
                                                                    
                                                                    @if($availableContacts->isEmpty())
                                                                        <div class="list-group-item py-3 text-center">
                                                                            <p class="mb-0 text-muted">No contacts found. <a href="{{ route('contacts.create') }}">Add a contact</a>.</p>
                                                                        </div>
                                                                    @else
                                                                        @foreach($availableContacts as $contact)
                                                                            <div class="list-group-item py-2 contact-item">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input contact-checkbox" type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" id="contact_{{ $contact->id }}">
                                                                                    <label class="form-check-label d-flex justify-content-between" for="contact_{{ $contact->id }}">
                                                                                        <span class="contact-name">{{ $contact->first_name }} {{ $contact->last_name }}</span>
                                                                                        <span class="contact-phone text-muted">{{ $contact->phone_number }}</span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 text-end">
                                                            <span id="selectedContactsCount" class="badge bg-primary">0 contact(s) selected</span>
                                                        </div>
                                                        @error('contact_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Contact Groups</label>
                                                        
                                                        @php 
                                                            // Get both personal and team-shared contact groups
                                                            $availableGroups = auth()->user()->contactGroups()->withCount('contacts')->get();
                                                            
                                                            // We should also get contact groups from teams where sharing is enabled
                                                            // This logic would need to be implemented in the TeamResourceService
                                                        @endphp
                                                        
                                                        @if($availableGroups->isEmpty())
                                                            <div class="alert alert-info">
                                                                <p class="mb-0">You don't have any contact groups. <a href="{{ route('contact-groups.create') }}">Create a group</a> first.</p>
                                                            </div>
                                                        @else
                                                            <div class="card">
                                                                <div class="card-body p-0" style="max-height: 250px; overflow-y: auto;">
                                                                    <div class="list-group list-group-flush">
                                                                        @foreach($availableGroups as $group)
                                                                            <div class="list-group-item py-2">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input contact-group-checkbox" type="checkbox" name="contact_group_ids[]" value="{{ $group->id }}" id="group_{{ $group->id }}" data-contacts="{{ $group->contacts_count }}">
                                                                                    <label class="form-check-label d-flex justify-content-between" for="group_{{ $group->id }}">
                                                                                        <span>{{ $group->name }}</span>
                                                                                        <span class="badge bg-light text-dark">{{ $group->contacts_count }} contacts</span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2 text-end">
                                                                <span id="groupRecipientsCount" class="badge bg-primary">0 recipient(s) selected</span>
                                                            </div>
                                                        @endif
                                                        @error('contact_group_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="all-contacts" role="tabpanel" aria-labelledby="all-contacts-tab">
                                                    <div class="mb-3">
                                                        <div class="alert alert-warning">
                                                            <p class="mb-0"><strong>Warning:</strong> This will send the message to all your contacts ({{ $totalContactsCount ?? 0 }} contacts). Use with caution.</p>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="send_to_all_contacts" id="sendToAllContacts" value="1" {{ old('send_to_all_contacts') ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="sendToAllContacts">
                                                                Yes, I want to send this message to all my contacts
                                                            </label>
                                                        </div>
                                                        @error('send_to_all_contacts')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="file-upload" role="tabpanel" aria-labelledby="file-tab">
                                                    <div class="mb-3">
                                                        <label for="recipients_file" class="form-label">Upload Recipients File</label>
                                                        <input type="file" name="recipients_file" id="recipients_file" class="form-control @error('recipients_file') is-invalid @enderror">
                                                        <div class="form-text text-muted">
                                                            Upload a CSV or TXT file with one phone number per line.
                                                        </div>
                                                        @error('recipients_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Optional scheduling -->
                                    <div class="mb-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="scheduleSwitch">
                                            <label class="form-check-label" for="scheduleSwitch">Schedule for later</label>
                                        </div>
                                        <div id="scheduleContainer" class="mt-3" style="display: none;">
                                            <label for="scheduled_at" class="form-label">Schedule Date & Time</label>
                                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at') }}">
                                            @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 mb-5">
                                        <button type="submit" class="btn btn-primary btn-lg" id="sendButton" {{ $senderNames->isEmpty() ? 'disabled' : '' }}>
                                            <i class="ki-outline ki-paper-plane fs-2 me-2"></i>
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
                                <!-- Message Summary Card -->
                                <div class="card card-bordered mb-5">
                                    <div class="card-header bg-primary">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-white">Message Summary</span>
                                        </h3>
                                    </div>
                                    <div class="card-body pt-5">
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
                                            <p class="mb-0"><small><i class="ki-outline ki-information-5 me-1"></i> This message will be sent as <span id="messageParts" class="fw-bold">0</span> part(s).</small></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips Card -->
                                <div class="card card-bordered mb-5">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Tips</span>
                                            <span class="text-gray-400 mt-1 fw-semibold fs-6">For better messaging</span>
                                        </h3>
                                        <div class="card-toolbar">
                                            <i class="ki-outline ki-lightbulb fs-2 text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-5">
                                        <ul class="mb-0">
                                            <li>Keep your message concise to reduce costs.</li>
                                            <li>Avoid special characters that might increase message parts.</li>
                                            <li>Include country code for all numbers (e.g. 233).</li>
                                            <li>Use contact groups for effective campaigns.</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Template Variables Card -->
                                <div class="card card-bordered">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Template Variables</span>
                                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Click to add to your message</span>
                                        </h3>
                                        <div class="card-toolbar">
                                            <i class="ki-outline ki-code fs-2 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="card-body pt-5">
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{name}">
                                                <i class="ki-outline ki-user fs-7 me-1"></i>Full Name
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{first_name}">
                                                <i class="ki-outline ki-user fs-7 me-1"></i>First Name
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{last_name}">
                                                <i class="ki-outline ki-user fs-7 me-1"></i>Last Name
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{dob}">
                                                <i class="ki-outline ki-calendar fs-7 me-1"></i>Date of Birth
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{company}">
                                                <i class="ki-outline ki-office-building fs-7 me-1"></i>Company
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{email}">
                                                <i class="ki-outline ki-sms fs-7 me-1"></i>Email
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-primary template-variable" data-variable="{phone}">
                                                <i class="ki-outline ki-phone fs-7 me-1"></i>Phone
                                            </button>
                                        </div>
                                        <div class="mt-3 fs-7 text-gray-600">
                                            <i class="ki-outline ki-information-5 text-info me-1"></i>
                                            Variables will be replaced with recipient data when message is sent.
                                        </div>
                                    </div>
                                </div>
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
        const recipientsTypeField = document.getElementById('recipients_type');
        const scheduleSwitch = document.getElementById('scheduleSwitch');
        const scheduleContainer = document.getElementById('scheduleContainer');
        const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
        const selectedContactsCount = document.getElementById('selectedContactsCount');
        const contactSearchInput = document.getElementById('contactSearchInput');
        const clearContactSearch = document.getElementById('clearContactSearch');
        const selectAllContactsButton = document.getElementById('selectAllContacts');
        
        let activeTab = 'manual'; // Track which tab is active

        // Set initial recipients type
        recipientsTypeField.value = 'multiple';

        // Initialize scheduling switch
        if (scheduleSwitch) {
            scheduleSwitch.addEventListener('change', function() {
                scheduleContainer.style.display = this.checked ? 'block' : 'none';
            });
        }
        
        // Process URL parameters for groups or contacts
        const urlParams = new URLSearchParams(window.location.search);
        const groupId = urlParams.get('group_id');
        const contactId = urlParams.get('contact_id');
        
        // Handle group_id parameter
        if (groupId) {
            // Activate groups tab
            const groupsTabEl = new bootstrap.Tab(document.getElementById('groups-tab'));
            groupsTabEl.show();
            activeTab = 'groups';
            recipientsTypeField.value = 'group';
            
            // Check the appropriate group checkbox
            const groupCheckbox = document.querySelector(`.contact-group-checkbox[value="${groupId}"]`);
            if (groupCheckbox) {
                groupCheckbox.checked = true;
            }
            
            // Update counts after a short delay to ensure DOM is updated
            setTimeout(updateRecipientCounts, 100);
        }
        
        // Handle contact_id parameter
        if (contactId) {
            // Activate manual tab
            const manualTabEl = new bootstrap.Tab(document.getElementById('manual-tab'));
            manualTabEl.show();
            activeTab = 'manual';
            recipientsTypeField.value = 'single';
            
            // CSRF token for API request
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Fetch contact details directly from backend using a POST request
            fetch('/fetch-contact-phone', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ contact_id: contactId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.phone_number) {
                    // Add the contact to the recipients field
                    recipientsField.value = data.phone_number;
                    
                    // Update counts
                    updateRecipientCounts();
                    console.log('Contact loaded successfully:', data);
                } else {
                    console.error('No phone number returned for contact');
                    recipientsField.value = 'Error loading phone number';
                }
            })
            .catch(error => {
                console.error('Error fetching contact:', error);
                recipientsField.value = 'Error loading phone number';
            });
        }

        // Tab handling - update recipients_type based on the active tab
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                activeTab = e.target.getAttribute('aria-controls');
                // Set the recipient type based on the tab
                if (activeTab === 'manual') {
                    recipientsTypeField.value = 'multiple';
                } else if (activeTab === 'select-contacts') {
                    recipientsTypeField.value = 'contacts';
                } else if (activeTab === 'groups') {
                    recipientsTypeField.value = 'group';
                } else if (activeTab === 'all-contacts') {
                    recipientsTypeField.value = 'all';
                } else if (activeTab === 'file-upload') {
                    recipientsTypeField.value = 'file';
                }
                
                console.log('Set recipients_type to:', recipientsTypeField.value);
                updateRecipientCounts();
            });
        });
        
        // Validate form before submission
        document.getElementById('smsForm').addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';
            
            // Check if sender name is selected
            const senderName = document.getElementById('sender_name').value;
            if (!senderName) {
                isValid = false;
                errorMessage = 'Please select a sender ID';
            }
            
            // Check if message is entered
            if (!messageField.value.trim()) {
                isValid = false;
                errorMessage = 'Please enter a message';
            }
            
            // Check recipients based on the active tab
            if (activeTab === 'manual' && (!recipientsField.value.trim() || getRecipientCount(recipientsField.value) === 0)) {
                isValid = false;
                errorMessage = 'Please enter at least one recipient phone number';
            } else if (activeTab === 'select-contacts' && document.querySelectorAll('.contact-checkbox:checked').length === 0) {
                isValid = false;
                errorMessage = 'Please select at least one contact';
            } else if (activeTab === 'groups' && document.querySelectorAll('.contact-group-checkbox:checked').length === 0) {
                isValid = false;
                errorMessage = 'Please select at least one contact group';
            } else if (activeTab === 'all-contacts' && !sendToAllContactsCheckbox.checked) {
                isValid = false;
                errorMessage = 'Please confirm sending to all contacts';
            } else if (activeTab === 'file-upload' && !document.getElementById('recipients_file').files.length) {
                isValid = false;
                errorMessage = 'Please upload a file with recipient phone numbers';
            }
            
            // Check scheduled time if scheduling is enabled
            if (scheduleSwitch && scheduleSwitch.checked) {
                const scheduledAt = document.getElementById('scheduled_at').value;
                if (!scheduledAt) {
                    isValid = false;
                    errorMessage = 'Please select a date and time for scheduling';
                } else {
                    // Check if the selected time is in the future
                    const scheduledTime = new Date(scheduledAt);
                    const now = new Date();
                    if (scheduledTime <= now) {
                        isValid = false;
                        errorMessage = 'Scheduled time must be in the future';
                    }
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
            
            // Set recipient type one last time before submission
            if (activeTab === 'manual') {
                const count = getRecipientCount(recipientsField.value);
                recipientsTypeField.value = count === 1 ? 'single' : 'multiple';
            }
            
            return true;
        });
        
        // Handle initial URL params for template
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

        // Contact selection
        contactCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateRecipientCounts);
        });

        // Contact search
        if (contactSearchInput) {
            contactSearchInput.addEventListener('input', function() {
                const searchTerm = contactSearchInput.value.toLowerCase();
                document.querySelectorAll('.contact-item').forEach(item => {
                    const name = item.querySelector('.contact-name').textContent.toLowerCase();
                    const phone = item.querySelector('.contact-phone').textContent.toLowerCase();
                    if (name.includes(searchTerm) || phone.includes(searchTerm)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // Clear contact search
        if (clearContactSearch) {
            clearContactSearch.addEventListener('click', function() {
                contactSearchInput.value = '';
                document.querySelectorAll('.contact-item').forEach(item => {
                    item.style.display = '';
                });
            });
        }

        // Select all contacts
        if (selectAllContactsButton) {
            selectAllContactsButton.addEventListener('click', function() {
                contactCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateRecipientCounts();
            });
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
        
        // Template variables insertion
        document.querySelectorAll('.template-variable').forEach(btn => {
            btn.addEventListener('click', function() {
                const variable = this.getAttribute('data-variable');
                insertAtCursor(messageField, variable);
                updateMessageMetrics();
            });
        });
        
        // Helper function to insert text at cursor position
        function insertAtCursor(field, text) {
            // Get cursor position
            const startPos = field.selectionStart;
            const endPos = field.selectionEnd;
            
            // Get current field value
            const currentValue = field.value;
            
            // Insert text at cursor position
            field.value = currentValue.substring(0, startPos) + 
                          text + 
                          currentValue.substring(endPos);
            
            // Set cursor position after inserted text
            const newPos = startPos + text.length;
            field.setSelectionRange(newPos, newPos);
            
            // Focus back on the field
            field.focus();
        }

        function loadTemplateContent(templateId) {
            // Show loading state
            messageField.disabled = true;
            messageField.value = 'Loading template...';
            
            // Fetch template content from server - use secure URL with location.protocol
            fetch(`${location.protocol}//${location.host}/sms/templates/${templateId}/content`)
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
            
            // Calculate message parts - FIXED FORMULA
            let parts = 1;
            if (length <= 160) {
                parts = 1;
            } else {
                // For multi-part messages, each part can hold 153 characters
                parts = Math.ceil(length / 153);
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
            else if (activeTab === 'select-contacts') {
                recipientCount = document.querySelectorAll('.contact-checkbox:checked').length;
                selectedContactsCount.textContent = recipientCount + ' contact(s) selected';
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
            else if (activeTab === 'file-upload') {
                // For file upload, we can't determine the exact count
                const fileInput = document.getElementById('recipients_file');
                recipientCount = fileInput.files.length > 0 ? 1 : 0; // Just indicate if a file is selected
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
                parts = Math.ceil(length / 153);
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
                    formData.append('recipients_count', recipientCount);
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
                        if (data.success) {
                            creditsNeeded.textContent = data.credits_needed;
                            messageParts.textContent = data.parts;
                            
                            if (data.parts > 1) {
                                messageInfo.style.display = 'block';
                            }
                            
                            // Display warning if user doesn't have enough credits
                            if (!data.has_enough_credits) {
                                // Check if warning already exists
                                let creditWarning = document.getElementById('credit-warning');
                                if (!creditWarning) {
                                    creditWarning = document.createElement('div');
                                    creditWarning.id = 'credit-warning';
                                    creditWarning.className = 'alert alert-danger mt-2';
                                    creditWarning.innerHTML = `<strong>Warning:</strong> You don't have enough credits. You need ${data.credits_needed} credits but have ${data.user_credits}.`;
                                    document.getElementById('sendButton').parentNode.insertBefore(creditWarning, document.getElementById('sendButton'));
                                } else {
                                    creditWarning.innerHTML = `<strong>Warning:</strong> You don't have enough credits. You need ${data.credits_needed} credits but have ${data.user_credits}.`;
                                }
                            } else {
                                // Remove warning if it exists
                                const creditWarning = document.getElementById('credit-warning');
                                if (creditWarning) {
                                    creditWarning.remove();
                                }
                            }
                            
                            console.log('API credits calculation:', data);
                        }
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