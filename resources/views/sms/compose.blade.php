@extends('layouts.master')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Compose & Dispatch SMS')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <!-- Welcome Banner Hero Card -->
                <div class="card mb-5 mb-xl-8 border-0 shadow-lg overflow-hidden" style="background: linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e293b 100%);">
                    <div class="card-body p-6 sm:p-8 text-white relative">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-6">
                            <div class="d-flex align-items-center gap-4">
                                <div class="symbol symbol-60px symbol-lg-75px symbol-circle border border-2 border-cyan-400/40 p-1 shadow-inner" style="background: rgba(14, 165, 233, 0.15);">
                                    <div class="symbol-label font-extrabold text-cyan-400 fs-1 rounded-circle" style="background: #0ea5e9; color: #ffffff;">
                                        <i class="ki-outline ki-message-text-2 fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Compose & Dispatch SMS</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Broadcast</span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 text-gray-400 fs-7 mt-2">
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-wallet fs-5 me-1 text-primary"></i>
                                            Available Credits: <strong class="text-white ms-1 me-2">{{ number_format(auth()->user()->sms_credits) }}</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-tag fs-5 me-1 text-success"></i>
                                            Rate: <strong class="text-white ms-1 me-2">{{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->getSmsRate(), 3) }}/SMS</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-element-11 me-1 fs-5"></i> SMS Overview
                                </a>
                                <a href="{{ route('sms.credits') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-plus-square me-1 fs-5"></i> Buy Credits
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-8">
                    <!-- Form Column -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Message Configuration</span>
                                    <span class="text-gray-400 mt-1 fs-7">Select sender ID, enter message text and choose target audience</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form method="POST" action="{{ route('sms.send') }}" id="smsForm" enctype="multipart/form-data">
                                    @csrf
                                    @if(session('error'))
                                        <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3">
                                            <i class="ki-outline ki-cross-circle fs-2x me-3 text-danger"></i>
                                            <span class="font-bold text-gray-800 fs-6">{{ session('error') }}</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Sender ID -->
                                    <div class="mb-5">
                                        <label for="sender_name" class="form-label font-bold text-gray-800 fs-6">Sender ID <span class="text-danger">*</span></label>
                                        <div class="position-relative">
                                            <select name="sender_name" id="sender_name" class="form-select form-select-solid ps-12 @error('sender_name') is-invalid @enderror" required>
                                                <option value="">Select an approved Sender ID</option>
                                                @foreach($senderNames as $senderName)
                                                    <option value="{{ $senderName->name }}" {{ old('sender_name') == $senderName->name ? 'selected' : '' }}>
                                                        {{ $senderName->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i class="ki-outline ki-shield-tick position-absolute top-50 translate-middle-y ms-4 fs-3 text-gray-400"></i>
                                        </div>
                                        @error('sender_name')<div class="invalid-feedback d-block mt-1 font-semibold">{{ $message }}</div>@enderror
                                        @if($senderNames->isEmpty())
                                            <div class="form-text text-danger font-medium mt-1.5">
                                                <i class="ki-outline ki-information-5 text-danger me-1"></i> You need to <a href="{{ route('sms.sender-names') }}" class="font-bold text-danger underline">register a sender ID</a> before sending SMS.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Campaign Name -->
                                    <div class="mb-5">
                                        <label for="campaign_name" class="form-label font-bold text-gray-800 fs-6">Campaign Name <span class="text-gray-400 font-normal fs-7">(Optional)</span></label>
                                        <div class="position-relative">
                                            <input type="text" name="campaign_name" id="campaign_name" class="form-control form-control-solid ps-12 @error('campaign_name') is-invalid @enderror" value="{{ old('campaign_name') }}" placeholder="e.g. Q3 Promotional Broadcast">
                                            <i class="ki-outline ki-tag position-absolute top-50 translate-middle-y ms-4 fs-3 text-gray-400"></i>
                                        </div>
                                        @error('campaign_name')<div class="invalid-feedback d-block mt-1 font-semibold">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Message Body -->
                                    <div class="mb-6">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label for="message" class="form-label font-bold text-gray-800 fs-6 mb-0">Message Text <span class="text-danger">*</span></label>
                                            <button type="button" class="btn btn-sm btn-light-primary rounded-pill font-bold shadow-xs px-3" data-bs-toggle="modal" data-bs-target="#templateModal">
                                                <i class="ki-outline ki-document me-1 fs-6"></i> Use Template
                                            </button>
                                        </div>
                                        <textarea name="message" id="message" rows="5" class="form-control form-control-solid @error('message') is-invalid @enderror" placeholder="Type your SMS copy here... You can insert variables from the sidebar." required>{{ request('template_content') ?: old('message') }}</textarea>
                                        <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                            <span id="characterCount" class="text-gray-500 font-semibold fs-8">0 characters</span>
                                            <span id="messageCount" class="badge badge-light-primary font-bold fs-8 px-2 py-0.5">0 page(s)</span>
                                        </div>
                                        @error('message')<div class="invalid-feedback d-block mt-1 font-semibold">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Hidden field for recipients_type -->
                                    <input type="hidden" name="recipients_type" id="recipients_type" value="single">

                                    <!-- Recipients Card Section -->
                                    <div class="card border border-gray-200 rounded-3 shadow-none mb-6">
                                        <div class="card-header border-0 pt-4 pb-0 bg-light-soft">
                                            <h4 class="card-title font-bold text-gray-900 fs-5 mb-0">Audience Recipients</h4>
                                        </div>
                                        <div class="card-body pt-3">
                                            <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch mb-5 fs-7 font-bold border-bottom" id="recipientsTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active py-3" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true" data-recipient-type="multiple">Enter Numbers</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link py-3" id="select-contacts-tab" data-bs-toggle="tab" data-bs-target="#select-contacts" type="button" role="tab" aria-controls="select-contacts" aria-selected="false" data-recipient-type="contacts">Select Contacts</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link py-3" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab" aria-controls="groups" aria-selected="false" data-recipient-type="group">Select Groups</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link py-3" id="all-contacts-tab" data-bs-toggle="tab" data-bs-target="#all-contacts" type="button" role="tab" aria-controls="all-contacts" aria-selected="false" data-recipient-type="all">All Contacts</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link py-3" id="file-tab" data-bs-toggle="tab" data-bs-target="#file-upload" type="button" role="tab" aria-controls="file-upload" aria-selected="false" data-recipient-type="file">Upload File</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="recipientsTabContent">
                                                <!-- Enter Numbers Tab -->
                                                <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                                                    <div class="mb-3">
                                                        <label for="recipients" class="form-label font-semibold text-gray-800">Phone Numbers</label>
                                                        <textarea name="recipients" id="recipients" rows="4" class="form-control form-control-solid @error('recipients') is-invalid @enderror" placeholder="Enter numbers separated by commas or new lines (e.g. 233240000000, 233500000000)">{{ old('recipients') }}</textarea>
                                                        <div class="d-flex justify-content-between align-items-center mt-1.5">
                                                            <small id="recipientCount" class="text-gray-500 font-bold">0 recipient(s)</small>
                                                        </div>
                                                        @error('recipients')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <!-- Select Contacts Tab -->
                                                <div class="tab-pane fade" id="select-contacts" role="tabpanel" aria-labelledby="select-contacts-tab">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <span class="font-semibold text-gray-800 fs-7">Audience Contact List</span>
                                                            <div class="input-group input-group-sm w-60">
                                                                <input type="text" id="contactSearchInput" class="form-control form-control-solid" placeholder="Search by name or phone...">
                                                                <button class="btn btn-outline-secondary" type="button" id="clearContactSearch">
                                                                    <i class="ki-outline ki-cross-circle fs-7"></i>
                                                                </button>
                                                                <button class="btn btn-primary font-bold" type="button" id="selectAllContacts">
                                                                    Select All
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="border rounded-3 bg-white">
                                                            <div class="p-0" style="max-height: 250px; overflow-y: auto;">
                                                                <div class="list-group list-group-flush">
                                                                    @php 
                                                                        $availableContacts = auth()->user()->getAvailableContacts()->sortBy('first_name');
                                                                    @endphp
                                                                    
                                                                    @if($availableContacts->isEmpty())
                                                                        <div class="list-group-item py-4 text-center">
                                                                            <p class="mb-0 text-gray-500 fs-7">No contacts found in audience. <a href="{{ route('contacts.create') }}" class="font-bold text-primary">Add a contact</a>.</p>
                                                                        </div>
                                                                    @else
                                                                        @foreach($availableContacts as $contact)
                                                                            <div class="list-group-item py-2.5 contact-item">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input contact-checkbox" type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" id="contact_{{ $contact->id }}">
                                                                                    <label class="form-check-label d-flex justify-content-between w-100 cursor-pointer" for="contact_{{ $contact->id }}">
                                                                                        <span class="contact-name font-bold text-gray-800 fs-7">{{ $contact->first_name }} {{ $contact->last_name }}</span>
                                                                                        <span class="contact-phone text-gray-500 font-mono fs-8 me-3">{{ $contact->phone_number }}</span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-2 text-end">
                                                            <span id="selectedContactsCount" class="badge badge-light-primary font-bold fs-8 px-2.5 py-1">0 contact(s) selected</span>
                                                        </div>
                                                        @error('contact_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <!-- Select Groups Tab -->
                                                <div class="tab-pane fade" id="groups" role="tabpanel" aria-labelledby="groups-tab">
                                                    <div class="mb-3">
                                                        <label class="form-label font-semibold text-gray-800">Target Contact Groups</label>
                                                        @php 
                                                            $availableGroups = auth()->user()->contactGroups()->withCount('contacts')->get();
                                                        @endphp
                                                        
                                                        @if($availableGroups->isEmpty())
                                                            <div class="alert alert-info p-4 rounded-3">
                                                                <p class="mb-0 fs-7">You don't have any contact groups saved yet. <a href="{{ route('contact-groups.create') }}" class="font-bold underline">Create a group</a> to broadcast to entire segments.</p>
                                                            </div>
                                                        @else
                                                            <div class="border rounded-3 bg-white">
                                                                <div class="p-0" style="max-height: 250px; overflow-y: auto;">
                                                                    <div class="list-group list-group-flush">
                                                                        @foreach($availableGroups as $group)
                                                                            <div class="list-group-item py-2.5">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input contact-group-checkbox" type="checkbox" name="contact_group_ids[]" value="{{ $group->id }}" id="group_{{ $group->id }}" data-contacts="{{ $group->contacts_count }}">
                                                                                    <label class="form-check-label d-flex justify-content-between align-items-center w-100 cursor-pointer" for="group_{{ $group->id }}">
                                                                                        <span class="font-bold text-gray-800 fs-7">{{ $group->name }}</span>
                                                                                        <span class="badge badge-light-info font-bold fs-8 px-2.5 py-1">{{ $group->contacts_count }} contacts</span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mt-2 text-end">
                                                                <span id="groupRecipientsCount" class="badge badge-light-primary font-bold fs-8 px-2.5 py-1">0 recipient(s) selected</span>
                                                            </div>
                                                        @endif
                                                        @error('contact_group_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <!-- All Contacts Tab -->
                                                <div class="tab-pane fade" id="all-contacts" role="tabpanel" aria-labelledby="all-contacts-tab">
                                                    <div class="mb-3">
                                                        <div class="alert alert-warning d-flex align-items-center p-4 rounded-3 mb-4">
                                                            <i class="ki-outline ki-shield-cross fs-2x me-3 text-warning"></i>
                                                            <div>
                                                                <span class="font-bold text-gray-900 fs-6">Mass Broadcast Alert</span>
                                                                <p class="mb-0 fs-7 text-gray-700">This action will broadcast this message to all <strong>{{ $totalContactsCount ?? 0 }}</strong> saved contacts in your audience database.</p>
                                                            </div>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="send_to_all_contacts" id="sendToAllContacts" value="1" {{ old('send_to_all_contacts') ? 'checked' : '' }}>
                                                            <label class="form-check-label font-bold text-gray-800 fs-7" for="sendToAllContacts">
                                                                Yes, I confirm I want to send this message to ALL {{ $totalContactsCount ?? 0 }} contacts
                                                            </label>
                                                        </div>
                                                        @error('send_to_all_contacts')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <!-- Upload File Tab -->
                                                <div class="tab-pane fade" id="file-upload" role="tabpanel" aria-labelledby="file-tab">
                                                    <div class="mb-3">
                                                        <label for="recipients_file" class="form-label font-semibold text-gray-800">Batch File Upload</label>
                                                        <input type="file" name="recipients_file" id="recipients_file" class="form-control form-control-solid @error('recipients_file') is-invalid @enderror">
                                                        <div class="form-text text-gray-400 fs-7 mt-1.5">
                                                            Upload a CSV or TXT file with one recipient phone number per line.
                                                        </div>
                                                        @error('recipients_file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Scheduling -->
                                    <div class="mb-6 bg-light-soft p-4 rounded-3 border border-gray-200">
                                        <div class="form-check form-switch d-flex align-items-center">
                                            <input class="form-check-input h-20px w-35px me-3" type="checkbox" id="scheduleSwitch">
                                            <label class="form-check-label font-bold text-gray-800 fs-6 cursor-pointer" for="scheduleSwitch">Schedule dispatch for a future date & time</label>
                                        </div>
                                        <div id="scheduleContainer" class="mt-4 pt-3 border-top border-gray-200" style="display: none;">
                                            <label for="scheduled_at" class="form-label font-semibold text-gray-800">Dispatch Time</label>
                                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control form-control-solid @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at') }}">
                                            @error('scheduled_at')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid gap-2 mb-4 mt-6">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 fs-4 font-extrabold rounded-xl shadow-md py-4" id="sendButton" {{ $senderNames->isEmpty() ? 'disabled' : '' }}>
                                            <i class="ki-outline ki-paper-plane fs-2 me-2" id="sendButtonIcon"></i>
                                            <span id="sendButtonText">Send SMS Dispatch</span>
                                        </button>
                                    </div>
                                    
                                    @if(!auth()->user()->isCustomer())
                                    <!-- Background Processing Callout -->
                                    <div class="alert alert-info d-flex align-items-start p-4 rounded-3" role="alert">
                                        <i class="ki-outline ki-information-5 fs-2x text-info me-3 mt-0.5"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="alert-heading font-bold fs-7 mb-1 text-gray-900">Background Queue Engine</h6>
                                            <p class="mb-0 fs-8 text-gray-600">SMS dispatches are queued and processed asynchronously in the background. You can monitor live delivery status on the campaign details page.</p>
                                        </div>
                                    </div>
                                    @endif
                                </form>

                                <!-- Template Modal -->
                                <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                      <div class="modal-header border-bottom-0 pb-0 pt-6 px-6">
                                        <h5 class="modal-title font-bold text-gray-900 fs-4" id="templateModalLabel">Select Saved Message Template</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body p-6">
                                        @if($templates->count())
                                           <div class="list-group">
                                             @foreach($templates as $template)
                                               <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                 <div class="me-3">
                                                   <div class="font-bold text-gray-900 fs-6 mb-1">{{ $template->name }}</div>
                                                   <p class="text-gray-500 fs-7 mb-0">{{ Str::limit($template->content, 90) }}</p>
                                                 </div>
                                                 <button type="button" class="btn btn-sm btn-primary font-bold use-template-btn shadow-xs px-4" data-id="{{ $template->id }}" data-bs-dismiss="modal">Use Template</button>
                                               </div>
                                             @endforeach
                                           </div>
                                        @else
                                           <p class="text-gray-500 text-center py-6 mb-0">No templates available. <a href="{{ route('sms.templates') }}" class="font-bold text-primary">Create a template</a>.</p>
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Summary Column -->
                    <div class="col-lg-4">
                        <div class="position-sticky top-0" style="padding-top: 0px;">
                            
                            <!-- Message Summary Card -->
                            <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-radius: 1.25rem;">
                                <div class="card-header pt-6 pb-2 border-0">
                                    <h3 class="card-title flex-column">
                                        <span class="card-label font-bold text-gray-900 fs-4">Dispatch Summary</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="d-flex flex-column align-items-center mb-6 py-5 bg-light-primary rounded-3 text-center">
                                        <span class="fs-8 font-bold text-primary text-uppercase tracking-wider">Estimated Credits Needed</span>
                                        <span id="creditsNeeded" class="fs-3hx font-extrabold text-primary lh-1 mt-2">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-3">
                                        <span class="text-gray-600 font-medium fs-7">Recipients Target:</span>
                                        <span id="recipientsCount" class="badge badge-light-primary font-bold fs-7">0</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-gray-600 font-medium fs-7">Length / Parts:</span>
                                        <span class="text-gray-800 font-bold fs-7"><span id="charsCount">0</span> chars</span>
                                    </div>
                                    <div class="alert alert-info mt-3 mb-0 p-3 rounded-3" id="messageInfo" style="display: none;">
                                        <p class="mb-0 fs-8"><i class="ki-outline ki-information-5 me-1"></i> Multi-page message: will consume <strong id="messageParts">0</strong> credits per recipient.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Template Variables Card -->
                            <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-radius: 1.25rem;">
                                <div class="card-header pt-5 pb-2 border-0">
                                    <h3 class="card-title flex-column">
                                        <span class="card-label font-bold text-gray-900 fs-5">Template Variables</span>
                                        <span class="text-gray-400 mt-1 fs-8">Click to insert tag into message copy</span>
                                    </h3>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{name}">
                                            <i class="ki-outline ki-user fs-7 me-1"></i>Full Name
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{first_name}">
                                            <i class="ki-outline ki-user fs-7 me-1"></i>First Name
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{last_name}">
                                            <i class="ki-outline ki-user fs-7 me-1"></i>Last Name
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{dob}">
                                            <i class="ki-outline ki-calendar fs-7 me-1"></i>Date of Birth
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{company}">
                                            <i class="ki-outline ki-office-building fs-7 me-1"></i>Company
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{email}">
                                            <i class="ki-outline ki-sms fs-7 me-1"></i>Email
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-primary font-bold template-variable" data-variable="{phone}">
                                            <i class="ki-outline ki-phone fs-7 me-1"></i>Phone
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Best Practices Card -->
                            <div class="card border-0 shadow-sm overflow-hidden bg-light-warning border-dashed border-warning/40" style="border-radius: 1.25rem;">
                                <div class="card-header pt-5 pb-2 border-0">
                                    <h3 class="card-title flex-column">
                                        <span class="card-label font-bold text-gray-900 fs-6">SMS Best Practices</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <i class="ki-outline ki-lightbulb fs-2 text-warning"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-2 fs-7 text-gray-700">
                                    <ul class="ps-4 mb-0">
                                        <li class="mb-1.5">Keep message under 160 chars for single-credit delivery.</li>
                                        <li class="mb-1.5">Include country prefix for international delivery.</li>
                                        <li class="mb-1.5">Use personalized variables for higher engagement.</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
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
            const sendButtonText = document.getElementById('sendButtonText');
            const sendButtonIcon = document.getElementById('sendButtonIcon');
            
            scheduleSwitch.addEventListener('change', function() {
                scheduleContainer.style.display = this.checked ? 'block' : 'none';
                
                // Update button text and icon based on scheduling
                if (this.checked) {
                    sendButtonText.textContent = 'Schedule Message';
                    sendButtonIcon.className = 'ki-outline ki-calendar fs-2 me-2';
                } else {
                    sendButtonText.textContent = 'Send SMS Dispatch';
                    sendButtonIcon.className = 'ki-outline ki-paper-plane fs-2 me-2';
                }
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
        
        // Character counter
        messageField.addEventListener('input', updateMessageMetrics);
        messageField.addEventListener('keyup', updateMessageMetrics);
        messageField.addEventListener('change', updateMessageMetrics);
        
        // Recipient counter
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

        // Initialize metrics on page load
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
            const startPos = field.selectionStart;
            const endPos = field.selectionEnd;
            const currentValue = field.value;
            field.value = currentValue.substring(0, startPos) + text + currentValue.substring(endPos);
            const newPos = startPos + text.length;
            field.setSelectionRange(newPos, newPos);
            field.focus();
        }

        function loadTemplateContent(templateId) {
            messageField.disabled = true;
            messageField.value = 'Loading template...';
            
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
            const length = messageField.value.length;
            characterCount.textContent = length + ' characters';
            charsCount.textContent = length;
            
            let parts = 1;
            if (length <= 160) {
                parts = 1;
            } else {
                parts = Math.ceil(length / 153);
            }
            
            messageCount.textContent = parts + ' page(s)';
            messageParts.textContent = parts;
            
            if (parts > 1) {
                messageInfo.style.display = 'block';
            } else {
                messageInfo.style.display = 'none';
            }
            
            calculateCredits();
        }
        
        function updateRecipientCounts() {
            let recipientCount = 0;
            
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
                const fileInput = document.getElementById('recipients_file');
                recipientCount = fileInput.files.length > 0 ? 1 : 0;
            }
            
            recipientsCount.textContent = recipientCount;
            calculateCredits();
        }
        
        function getRecipientCount(text) {
            if (!text.trim()) return 0;
            const recipients = text.split(/[\s,\n]+/).filter(item => item.trim() !== '');
            return recipients.length;
        }
        
        function calculateCredits() {
            const message = messageField.value;
            const recipientCount = parseInt(recipientsCount.textContent) || 0;
            
            let parts = 1;
            const length = message.length;
            const hasUnicode = hasUnicodeCharacters(message);
            
            if (hasUnicode) {
                parts = length <= 70 ? 1 : Math.ceil(length / 67);
            } else {
                parts = length <= 160 ? 1 : Math.ceil(length / 153);
            }
            
            const estimatedCredits = parts * recipientCount;
            creditsNeeded.textContent = estimatedCredits;
            
            if (message && recipientCount > 0) {
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
                            } else {
                                messageInfo.style.display = 'none';
                            }
                            
                            if (!data.has_enough_credits) {
                                let creditWarning = document.getElementById('credit-warning');
                                if (!creditWarning) {
                                    creditWarning = document.createElement('div');
                                    creditWarning.id = 'credit-warning';
                                    creditWarning.className = 'alert alert-danger mt-2 font-semibold';
                                    creditWarning.innerHTML = `<strong>Warning:</strong> You don't have enough credits. You need ${data.credits_needed} credits but have ${data.user_credits}.`;
                                    document.getElementById('sendButton').parentNode.insertBefore(creditWarning, document.getElementById('sendButton'));
                                } else {
                                    creditWarning.innerHTML = `<strong>Warning:</strong> You don't have enough credits. You need ${data.credits_needed} credits but have ${data.user_credits}.`;
                                }
                            } else {
                                const creditWarning = document.getElementById('credit-warning');
                                if (creditWarning) {
                                    creditWarning.remove();
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error calculating credits:', error);
                    });
                }, 300);
            }
        }

        function hasUnicodeCharacters(message) {
            const nonGsmPattern = /[^\u0000-\u007F\u00A0-\u00FF\u2010-\u2019\u201C-\u201D\u2026\u20AC]/;
            return nonGsmPattern.test(message);
        }
    });
</script>
@endpush