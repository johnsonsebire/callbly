@extends('layouts.master')

@section('title', 'Export Contacts Directory')

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
                                        <i class="ki-outline ki-exit-down fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Export Contacts Directory</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Data Download</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Export customer contacts and segment data to Excel (.xlsx), CSV, or printable PDF formats.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('contacts.index') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-arrow-left me-1 fs-5"></i> Back to Directory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                        <div>
                            <span class="font-bold text-gray-900 fs-6">Export Configuration Errors</span>
                            <ul class="mb-0 fs-7 text-gray-700 ps-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="row g-5 g-xl-8">
                    <!-- Export Form Column -->
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Export Configuration</span>
                                    <span class="text-gray-400 mt-1 fs-7">Select records scope, target file format, and data columns</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <form action="{{ route('contacts.process-export') }}" method="GET">
                                    
                                    <!-- Export Type Options -->
                                    <div class="mb-6">
                                        <label class="form-label font-bold text-gray-800 fs-6 mb-3">Record Scope to Export</label>
                                        <div class="d-flex flex-column gap-2.5 bg-light-soft p-4 rounded-3 border border-gray-200">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="export_type" id="exportAll" value="all" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="exportAll">
                                                    All Audience Contacts (Full Database)
                                                </label>
                                            </div>
                                            @if(count($groups) > 0)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="export_type" id="exportGroup" value="group" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="exportGroup">
                                                    Specific Contact Group Segment
                                                </label>
                                            </div>
                                            @endif
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="export_type" id="exportSelected" value="selected" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="exportSelected">
                                                    Selected Contacts Only
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Group Selection -->
                                    @if(count($groups) > 0)
                                    <div class="mb-6" id="groupSelectContainer" style="display: none;">
                                        <label for="group_id" class="form-label font-bold text-gray-800 fs-6 mb-2">Select Target Group</label>
                                        <select class="form-select form-select-solid" id="group_id" name="group_id">
                                            <option value="">-- Select a group --</option>
                                            @foreach($groups as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    
                                    <!-- Contact Selection Table -->
                                    <div class="mb-6" id="contactSelectContainer" style="display: none;">
                                        <label class="form-label font-bold text-gray-800 fs-6 mb-2">Select Individual Contacts</label>
                                        <div class="border rounded-3 bg-white" style="max-height: 300px; overflow-y: auto;">
                                            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-3 mb-0">
                                                <thead class="sticky-top bg-light-soft">
                                                    <tr class="fw-bold text-gray-400 fs-8 uppercase">
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
                                                            <td class="font-bold text-gray-900 fs-7">{{ $contact->full_name }}</td>
                                                            <td class="font-mono text-gray-700 fs-7">{{ $contact->phone_number }}</td>
                                                            <td class="text-gray-600 fs-7">{{ $contact->email ?? '—' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-4 text-gray-500 fs-7">No contacts found in audience</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <span class="badge badge-light-primary font-bold fs-8 px-2.5 py-1">
                                                <span id="selectedContactsCount">0</span> contacts selected
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="selected_contacts" id="selectedContactIds" value="">
                                    
                                    <!-- Format Options -->
                                    <div class="mb-6">
                                        <label class="form-label font-bold text-gray-800 fs-6 mb-3">Export File Format</label>
                                        <div class="row g-3">
                                            <!-- Excel Option -->
                                            <div class="col-md-4">
                                                <label class="format-card-option d-flex align-items-center p-4 border rounded-3 cursor-pointer h-100" for="formatExcel">
                                                    <input class="form-check-input me-3 flex-shrink-0" type="radio" name="format" id="formatExcel" value="excel" checked />
                                                    <div class="symbol symbol-40px me-3 flex-shrink-0">
                                                        <span class="symbol-label bg-light-success rounded-circle">
                                                            <span class="material-icons fs-2 text-success">description</span>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="font-extrabold text-gray-900 fs-6">Excel</span>
                                                        <span class="text-gray-500 font-mono fs-8">.xlsx format</span>
                                                    </div>
                                                </label>
                                            </div>

                                            <!-- CSV Option -->
                                            <div class="col-md-4">
                                                <label class="format-card-option d-flex align-items-center p-4 border rounded-3 cursor-pointer h-100" for="formatCsv">
                                                    <input class="form-check-input me-3 flex-shrink-0" type="radio" name="format" id="formatCsv" value="csv" />
                                                    <div class="symbol symbol-40px me-3 flex-shrink-0">
                                                        <span class="symbol-label bg-light-primary rounded-circle">
                                                            <span class="material-icons fs-2 text-primary">table_chart</span>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="font-extrabold text-gray-900 fs-6">CSV File</span>
                                                        <span class="text-gray-500 font-mono fs-8">.csv format</span>
                                                    </div>
                                                </label>
                                            </div>

                                            <!-- PDF Option -->
                                            <div class="col-md-4">
                                                <label class="format-card-option d-flex align-items-center p-4 border rounded-3 cursor-pointer h-100" for="formatPdf">
                                                    <input class="form-check-input me-3 flex-shrink-0" type="radio" name="format" id="formatPdf" value="pdf" />
                                                    <div class="symbol symbol-40px me-3 flex-shrink-0">
                                                        <span class="symbol-label bg-light-danger rounded-circle">
                                                            <span class="material-icons fs-2 text-danger">picture_as_pdf</span>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <span class="font-extrabold text-gray-900 fs-6">PDF Document</span>
                                                        <span class="text-gray-500 font-mono fs-8">.pdf format</span>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Column Selection -->
                                    <div class="mb-6">
                                        <label class="form-label font-bold text-gray-800 fs-6 mb-3">Columns to Include</label>
                                        <div class="d-flex flex-wrap gap-4 bg-light-soft p-4 rounded-3 border border-gray-200">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colFirstName" value="first_name" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colFirstName">First Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colLastName" value="last_name" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colLastName">Last Name</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colPhone" value="phone_number" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colPhone">Phone Number</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colEmail" value="email" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colEmail">Email</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colCompany" value="company" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colCompany">Company</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colDateOfBirth" value="date_of_birth" checked />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colDateOfBirth">Date of Birth</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colGender" value="gender" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colGender">Gender</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colCountry" value="country" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colCountry">Country</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colRegion" value="region" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colRegion">Region/State</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colCity" value="city" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colCity">City</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="columns[]" id="colNotes" value="notes" />
                                                <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colNotes">Notes</label>
                                            </div>
                                        </div>
                                        
                                        <!-- Custom Fields Section -->
                                        @if(isset($customFields) && $customFields->count() > 0)
                                        <div class="mt-4 pt-4 border-top border-gray-200">
                                            <label class="form-label font-bold text-primary fs-7 mb-2">Custom Fields</label>
                                            <div class="d-flex flex-wrap gap-4">
                                                @foreach($customFields as $customField)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="custom_fields[]" 
                                                           id="colCustom{{ $customField->id }}" value="{{ $customField->name }}" />
                                                    <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="colCustom{{ $customField->id }}">
                                                        {{ $customField->label }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-3 mt-6">
                                        <a href="{{ route('contacts.index') }}" class="btn btn-light font-bold px-5">Cancel</a>
                                        <button type="submit" class="btn btn-primary font-bold px-6 py-3 shadow-sm">
                                            <i class="ki-outline ki-download fs-4 me-2"></i>Export Contacts Now
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Information Sidebar Column -->
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 bg-light-soft" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0 bg-transparent">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-5">Export Overview</span>
                                    <span class="text-gray-400 mt-1 fs-8">Backup and external reporting</span>
                                </h3>
                                <div class="card-toolbar">
                                    <i class="ki-outline ki-information-5 fs-2 text-primary"></i>
                                </div>
                            </div>
                            <div class="card-body pt-2 fs-7 text-gray-700">
                                <ul class="ps-4 mb-3">
                                    <li class="mb-2">Export full audience database or specific segment groups.</li>
                                    <li class="mb-2">Includes custom contact parameters and phone numbers.</li>
                                    <li class="mb-2">Downloads immediately to your local system.</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.25rem;">
                            <div class="card-header pt-5 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-6">Format Capabilities</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2">
                                <div class="d-flex align-items-center mb-3 p-3 bg-light-success rounded-3">
                                    <span class="material-icons fs-1 text-success me-3">description</span>
                                    <div>
                                        <div class="font-bold text-success fs-7">Excel (.xlsx)</div>
                                        <div class="text-gray-600 fs-8">Best for data analysis & filtering</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3 p-3 bg-light-primary rounded-3">
                                    <span class="material-icons fs-1 text-primary me-3">table_chart</span>
                                    <div>
                                        <div class="font-bold text-primary fs-7">CSV (.csv)</div>
                                        <div class="text-gray-600 fs-8">Universal software compatibility</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center p-3 bg-light-danger rounded-3">
                                    <span class="material-icons fs-1 text-danger me-3">picture_as_pdf</span>
                                    <div>
                                        <div class="font-bold text-danger fs-7">PDF (.pdf)</div>
                                        <div class="text-gray-600 fs-8">Ideal for printing & documentation</div>
                                    </div>
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

@push('styles')
<style>
    .format-card-option {
        background: #ffffff;
        border: 1.5px solid #e2e8f0 !important;
        transition: all 0.2s ease-in-out;
        border-radius: 0.85rem !important;
    }
    
    .format-card-option:hover {
        border-color: #0284c7 !important;
        background-color: #f8fafc;
    }

    .format-card-option:has(input[type="radio"]:checked) {
        border-color: #0284c7 !important;
        background-color: rgba(2, 132, 199, 0.04);
        box-shadow: 0 4px 12px rgba(2, 132, 199, 0.08);
    }
</style>
@endpush

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
            
            handleExportTypeChange();
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

            handleExportTypeChange();
            document.querySelectorAll('input[name="export_type"]').forEach(radio => {
                radio.addEventListener('change', handleExportTypeChange);
            });

            const updateSelectedContacts = function() {
                const selectedContactIds = Array.from(contactCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                selectedContactIdsInput.value = selectedContactIds.join(',');
                selectedContactsCount.textContent = selectedContactIds.length;
            };

            contactCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedContacts);
            });

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
            if (exportSelectedRadio) {
                exportSelectedRadio.checked = true;
                if (contactSelectContainer) contactSelectContainer.style.display = 'block';
            }
        }
    });
</script>
@endpush