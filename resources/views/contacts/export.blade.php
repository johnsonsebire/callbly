@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Export Contacts Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Export Contacts</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Export your contacts to PDF or Excel format</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Contacts
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-lg-8">
                                <form action="{{ route('contacts.process-export') }}" method="GET" class="mb-5">
                                    <div class="card card-bordered mb-5">
                                        <div class="card-header">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label fw-bold text-dark">Export Options</span>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <!-- Export Type Options -->
                                            <div class="mb-5">
                                                <label class="form-label fw-semibold">What to Export</label>
                                                <div class="d-flex flex-column">
                                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio" name="export_type" id="exportAll" value="all" checked />
                                                        <label class="form-check-label fw-semibold" for="exportAll">
                                                            All Contacts
                                                        </label>
                                                    </div>
                                                    @if(count($groups) > 0)
                                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio" name="export_type" id="exportGroup" value="group" />
                                                        <label class="form-check-label fw-semibold" for="exportGroup">
                                                            Specific Group
                                                        </label>
                                                    </div>
                                                    @endif
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" name="export_type" id="exportSelected" value="selected" />
                                                        <label class="form-check-label fw-semibold" for="exportSelected">
                                                            Selected Contacts
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Group Selection (appears when "Specific Group" is selected) -->
                                            @if(count($groups) > 0)
                                            <div class="mb-5" id="groupSelectContainer" style="display: none;">
                                                <label for="group_id" class="form-label fw-semibold">Select a Group</label>
                                                <select class="form-select form-select-solid" id="group_id" name="group_id">
                                                    <option value="">-- Select a group --</option>
                                                    @foreach($groups as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endif
                                            
                                            <!-- Contact Selection (appears when "Selected Contacts" is chosen) -->
                                            <div class="mb-5" id="contactSelectContainer" style="display: none;">
                                                <label class="form-label fw-semibold">Select Contacts to Export</label>
                                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-2">
                                                        <thead class="sticky-top bg-white">
                                                            <tr class="fw-bold text-muted">
                                                                <th class="min-w-25px ps-3">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" id="selectAllContactsCheckbox">
                                                                    </div>
                                                                </th>
                                                                <th class="min-w-150px">Name</th>
                                                                <th class="min-w-120px">Phone</th>
                                                                <th class="min-w-150px">Email</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($allContacts as $contact)
                                                                <tr>
                                                                    <td class="ps-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input contact-select-checkbox" 
                                                                                   type="checkbox" 
                                                                                   value="{{ $contact->id }}"
                                                                                   data-contact-id="{{ $contact->id }}">
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $contact->full_name }}</td>
                                                                    <td>{{ $contact->phone_number }}</td>
                                                                    <td>{{ $contact->email ?? '-' }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center py-5">
                                                                        <div class="text-gray-600 fs-5">No contacts found</div>
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="fs-7 text-gray-600 mt-2">
                                                    <span id="selectedContactsCount">0</span> contacts selected
                                                </div>
                                            </div>
                                            
                                            <!-- Selected Contacts Input (hidden, populated by JavaScript) -->
                                            <input type="hidden" name="selected_contacts" id="selectedContactIds" value="">
                                            
                                            <!-- Format Options -->
                                            <div class="mb-5">
                                                <label class="form-label fw-semibold">Export Format</label>
                                                <div class="d-flex flex-column">
                                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio" name="format" id="formatExcel" value="excel" checked />
                                                        <label class="form-check-label fw-semibold" for="formatExcel">
                                                            Excel (.xlsx)
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio" name="format" id="formatCsv" value="csv" />
                                                        <label class="form-check-label fw-semibold" for="formatCsv">
                                                            CSV (.csv)
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" name="format" id="formatPdf" value="pdf" />
                                                        <label class="form-check-label fw-semibold" for="formatPdf">
                                                            PDF (.pdf)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Column Selection -->
                                            <div class="mb-5">
                                                <label class="form-label fw-semibold">Columns to Include</label>
                                                <div class="d-flex flex-wrap">
                                                    <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colFirstName" value="first_name" checked />
                                                        <label class="form-check-label" for="colFirstName">First Name</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colLastName" value="last_name" checked />
                                                        <label class="form-check-label" for="colLastName">Last Name</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colPhone" value="phone_number" checked />
                                                        <label class="form-check-label" for="colPhone">Phone Number</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colEmail" value="email" checked />
                                                        <label class="form-check-label" for="colEmail">Email</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colCompany" value="company" checked />
                                                        <label class="form-check-label" for="colCompany">Company</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid me-5 mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colDateOfBirth" value="date_of_birth" checked />
                                                        <label class="form-check-label" for="colDateOfBirth">Date of Birth</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="checkbox" name="columns[]" id="colNotes" value="notes" />
                                                        <label class="form-check-label" for="colNotes">Notes</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('contacts.index') }}" class="btn btn-light me-3">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ki-outline ki-download fs-2 me-2"></i>Export Contacts
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card card-bordered bg-light-info">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Export Information</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-5">
                                            <p class="text-gray-800 fs-6 fw-semibold mb-2">Export Options:</p>
                                            <ul class="ps-4">
                                                <li class="mb-1">Export all contacts in your account</li>
                                                <li class="mb-1">Export contacts from a specific group</li>
                                                <li>Export only selected contacts from the contacts list</li>
                                            </ul>
                                        </div>
                                        <div class="separator my-5"></div>
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-information-5 fs-2 text-info me-3"></i>
                                            <div class="text-gray-800 fs-6 fw-semibold">You can use the exported data for backup purposes or to import into other systems.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card card-bordered mt-5">
                                    <div class="card-header">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark">Format Options</span>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-success rounded-circle w-40px h-40px me-3">
                                                <i class="ki-outline ki-file-excel fs-1 text-success"></i>
                                            </div>
                                            <div>
                                                <div class="fs-6 fw-bold text-dark">Excel Format</div>
                                                <div class="fs-7 text-muted">Perfect for data analysis and manipulation</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="d-flex align-items-center justify-content-center bg-light-primary rounded-circle w-40px h-40px me-3">
                                                <i class="ki-outline ki-file-csv fs-1 text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fs-6 fw-bold text-dark">CSV Format</div>
                                                <div class="fs-7 text-muted">Universal compatibility with most systems</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center bg-light-danger rounded-circle w-40px h-40px me-3">
                                                <i class="ki-outline ki-file-pdf fs-1 text-danger"></i>
                                            </div>
                                            <div>
                                                <div class="fs-6 fw-bold text-dark">PDF Format</div>
                                                <div class="fs-7 text-muted">Great for printing or formal documentation</div>
                                            </div>
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
        // Group selection toggle
        const exportGroupRadio = document.getElementById('exportGroup');
        const groupSelectContainer = document.getElementById('groupSelectContainer');
        
        if (exportGroupRadio && groupSelectContainer) {
            const handleExportTypeChange = function() {
                groupSelectContainer.style.display = exportGroupRadio.checked ? 'block' : 'none';
            };
            
            // Initial setup
            handleExportTypeChange();
            
            // Add event listeners to all export type radios
            document.querySelectorAll('input[name="export_type"]').forEach(radio => {
                radio.addEventListener('change', handleExportTypeChange);
            });
        }
        
        // Contact selection toggle
        const exportSelectedRadio = document.getElementById('exportSelected');
        const contactSelectContainer = document.getElementById('contactSelectContainer');
        const selectedContactIdsInput = document.getElementById('selectedContactIds');
        const selectedContactsCount = document.getElementById('selectedContactsCount');
        const selectAllContactsCheckbox = document.getElementById('selectAllContactsCheckbox');
        const contactCheckboxes = document.querySelectorAll('.contact-select-checkbox');

        if (exportSelectedRadio && contactSelectContainer) {
            const handleExportTypeChange = function() {
                contactSelectContainer.style.display = exportSelectedRadio.checked ? 'block' : 'none';
            };

            // Initial setup
            handleExportTypeChange();

            // Add event listeners to all export type radios
            document.querySelectorAll('input[name="export_type"]').forEach(radio => {
                radio.addEventListener('change', handleExportTypeChange);
            });

            // Update selected contacts input and count
            const updateSelectedContacts = function() {
                const selectedContactIds = Array.from(contactCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                selectedContactIdsInput.value = selectedContactIds.join(',');
                selectedContactsCount.textContent = selectedContactIds.length;
            };

            // Add event listeners to contact checkboxes
            contactCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedContacts);
            });

            // Select all contacts
            if (selectAllContactsCheckbox) {
                selectAllContactsCheckbox.addEventListener('change', function() {
                    contactCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllContactsCheckbox.checked;
                    });
                    updateSelectedContacts();
                });
            }
        }
        
        // Pre-populate selected contacts if coming from contacts list
        const urlParams = new URLSearchParams(window.location.search);
        const selectedContacts = urlParams.get('selected_contacts');
        
        if (selectedContacts) {
            document.getElementById('selectedContactIds').value = selectedContacts;
            document.getElementById('exportSelected').checked = true;
        }
    });
</script>
@endpush