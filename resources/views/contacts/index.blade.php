@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Contacts Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">My Contacts</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage your contact list</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary me-2">
                                <i class="ki-outline ki-plus-square fs-2 me-2"></i> Add Contact
                            </a>
                            <a href="{{ route('contacts.import') }}" class="btn btn-sm btn-light-primary me-2">
                                <i class="ki-outline ki-upload fs-2 me-2"></i> Import
                            </a>
                            <a href="{{ route('contacts.export') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-download fs-2 me-2"></i> Export
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        @if(session('errors') && is_array(session('errors')) && count(session('errors')) > 0)
                            <div class="alert alert-danger">
                                <p><strong>The following errors occurred:</strong></p>
                                <ul>
                                    @foreach(session('errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div id="exportOptions" class="mb-4" style="display: none;">
                            <div class="card card-bordered">
                                <div class="card-body bg-light">
                                    <h6 class="mb-3">Export Options</h6>
                                    <form action="{{ route('contacts.export') }}" method="GET">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="export_type" id="exportAll" value="all" checked>
                                                    <label class="form-check-label" for="exportAll">
                                                        Export all contacts
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="export_type" id="exportSelected" value="selected">
                                                    <label class="form-check-label" for="exportSelected">
                                                        Export selected contacts
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="groupFilter" class="form-label">Filter by group (optional)</label>
                                                <select class="form-select form-select-sm" name="group_id" id="groupFilter">
                                                    <option value="">All groups</option>
                                                    @foreach(Auth::user()->contactGroups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary mt-4">Export Contacts</button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="selected_contacts" id="selectedContactIds" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-25px">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllContacts">
                                            </div>
                                        </th>
                                        <th class="min-w-150px">Name</th>
                                        <th class="min-w-120px">Phone</th>
                                        <th class="min-w-150px">Email</th>
                                        <th class="min-w-120px">Company</th>
                                        <th class="min-w-100px">Status</th>
                                        <th class="min-w-150px">Groups</th>
                                        <th class="min-w-150px text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($contacts as $contact)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input contact-checkbox" type="checkbox" value="{{ $contact->id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-45px me-5">
                                                        <div class="symbol-label bg-light-primary text-primary fs-6 fw-bold">
                                                            {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start flex-column">
                                                        <a href="{{ route('contacts.show', $contact) }}" class="text-dark fw-bold text-hover-primary mb-1 fs-6">
                                                            {{ $contact->full_name }}
                                                        </a>
                                                        @if($contact->job_title)
                                                            <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                                {{ $contact->job_title }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    {{ $contact->phone_number }}
                                                    @if($contact->whatsapp_number || ($contact->phone_number && $contact->preferred_contact_method === 'whatsapp'))
                                                        <a href="#" onclick="openWhatsApp('{{ $contact->whatsapp_number ?: $contact->phone_number }}')" class="btn btn-icon btn-sm btn-success ms-2" title="Send WhatsApp message">
                                                            <i class="fab fa-whatsapp fs-6"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $contact->email ?: '-' }}</td>
                                            <td>{{ $contact->company ?: '-' }}</td>
                                            <td>
                                                <x-crm-badges :contact="$contact" />
                                            </td>
                                            <td>
                                                @if($contact->groups && $contact->groups->count() > 0)
                                                    @foreach($contact->groups as $group)
                                                        <span class="badge badge-light-secondary me-1 mb-1">{{ $group->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('contacts.show', $contact) }}" class="btn btn-sm btn-light-info me-1" title="View Details">
                                                    <i class="ki-outline ki-eye fs-5"></i>
                                                </a>
                                                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-light-primary me-1" title="Edit Contact">
                                                    <i class="ki-outline ki-pencil fs-5"></i>
                                                </a>
                                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this contact?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger me-1" title="Delete Contact">
                                                        <i class="ki-outline ki-trash fs-5"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('sms.compose') }}?contact_id={{ $contact->id }}" class="btn btn-sm btn-light-success" title="Send SMS">
                                                    <i class="ki-outline ki-message-text-2 fs-5"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-10">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                                        <div class="symbol-label bg-light-primary">
                                                            <i class="ki-outline ki-profile-user fs-1 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-gray-600 fs-5 mb-5">No contacts found</div>
                                                    <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                                                        <i class="ki-outline ki-plus-square fs-3 me-2"></i>Add Your First Contact
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $contacts->links() }}
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
        // Toggle export options panel
        const exportBtn = document.getElementById('exportContactsBtn');
        const exportOptions = document.getElementById('exportOptions');
        
        exportBtn.addEventListener('click', function() {
            exportOptions.style.display = exportOptions.style.display === 'none' ? 'block' : 'none';
        });
        
        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAllContacts');
        const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
        
        selectAllCheckbox.addEventListener('change', function() {
            contactCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedContactsInput();
        });
        
        contactCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedContactsInput();
            });
        });
        
        // Update hidden input with selected contact IDs
        function updateSelectedContactsInput() {
            const selectedIds = Array.from(contactCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            
            document.getElementById('selectedContactIds').value = selectedIds.join(',');
            
            // Auto-select "Export selected" when contacts are checked
            if (selectedIds.length > 0) {
                document.getElementById('exportSelected').checked = true;
            } else {
                document.getElementById('exportAll').checked = true;
            }
        }
    });

    // WhatsApp functionality
    function openWhatsApp(phoneNumber) {
        // Clean phone number (remove spaces, dashes, etc.)
        const cleanNumber = phoneNumber.replace(/[^\d+]/g, '');
        
        // Open WhatsApp with the phone number
        const whatsappUrl = `https://wa.me/${cleanNumber}`;
        window.open(whatsappUrl, '_blank');
    }
</script>
@endpush