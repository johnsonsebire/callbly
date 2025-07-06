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
                        
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
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

                        <!-- Search and Filter Section -->
                        <div class="d-flex flex-stack flex-wrap mb-5">
                            <div class="d-flex align-items-center position-relative my-1 me-5">
                                <i class="ki-outline ki-magnifier fs-1 position-absolute ms-4"></i>
                                <input type="text" data-kt-contacts-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search contacts..." id="contactSearch" />
                            </div>
                            <div class="d-flex align-items-center">
                                <select class="form-select form-select-solid me-3" data-control="select2" data-placeholder="Filter by group" id="groupFilter" style="width: 200px;">
                                    <option value="all">All Groups</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-light-primary me-3" id="exportContactsBtn">
                                    <i class="ki-outline ki-download fs-2 me-2"></i>Export Options
                                </button>
                            </div>
                        </div>
                        
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
                                                <label for="exportGroupFilter" class="form-label">Filter by group (optional)</label>
                                                <select class="form-select form-select-sm" name="group_id" id="exportGroupFilter">
                                                    <option value="">All groups</option>
                                                    @foreach($groups as $group)
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
                        
                        <!-- Loading indicator -->
                        <div id="contactsLoading" class="d-none text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        
                        <!-- Contacts Table Container -->
                        <div id="contactsTableContainer">
                            @include('contacts.partials.contacts_table')
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
        let searchTimeout;
        const searchInput = document.getElementById('contactSearch');
        const groupFilter = document.getElementById('groupFilter');
        const contactsTableContainer = document.getElementById('contactsTableContainer');
        const loadingIndicator = document.getElementById('contactsLoading');
        
        // Real-time search function
        function performSearch() {
            const searchTerm = searchInput.value.trim();
            const selectedGroup = groupFilter.value;
            
            // Show loading indicator
            loadingIndicator.classList.remove('d-none');
            contactsTableContainer.style.opacity = '0.5';
            
            // Build URL with parameters
            const url = new URL('{{ route("contacts.search") }}', window.location.origin);
            if (searchTerm) {
                url.searchParams.append('q', searchTerm);
            }
            if (selectedGroup && selectedGroup !== 'all') {
                url.searchParams.append('group', selectedGroup);
            }
            
            // Make AJAX request
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            })
            .then(response => response.text())
            .then(html => {
                contactsTableContainer.innerHTML = html;
                loadingIndicator.classList.add('d-none');
                contactsTableContainer.style.opacity = '1';
                
                // Reinitialize event listeners for the new content
                initializeTableEvents();
            })
            .catch(error => {
                console.error('Search error:', error);
                loadingIndicator.classList.add('d-none');
                contactsTableContainer.style.opacity = '1';
            });
        }
        
        // Search input with debouncing
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300); // 300ms delay
        });
        
        // Group filter change
        groupFilter.addEventListener('change', function() {
            performSearch();
        });
        
        // Initialize table events (for both initial load and after AJAX updates)
        function initializeTableEvents() {
            // Select all checkbox functionality
            const selectAllCheckbox = document.getElementById('selectAllContacts');
            const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
            
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    contactCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateSelectedContactsInput();
                });
            }
            
            contactCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedContactsInput();
                    
                    // Update select all checkbox state
                    if (selectAllCheckbox) {
                        const allChecked = Array.from(contactCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(contactCheckboxes).some(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    }
                });
            });
        }
        
        // Update hidden input with selected contact IDs
        function updateSelectedContactsInput() {
            const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
            const selectedIds = Array.from(contactCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            
            const selectedContactIdsInput = document.getElementById('selectedContactIds');
            if (selectedContactIdsInput) {
                selectedContactIdsInput.value = selectedIds.join(',');
            }
            
            // Auto-select "Export selected" when contacts are checked
            const exportSelected = document.getElementById('exportSelected');
            const exportAll = document.getElementById('exportAll');
            if (selectedIds.length > 0 && exportSelected) {
                exportSelected.checked = true;
            } else if (exportAll) {
                exportAll.checked = true;
            }
        }
        
        // Toggle export options panel
        const exportBtn = document.getElementById('exportContactsBtn');
        const exportOptions = document.getElementById('exportOptions');
        
        if (exportBtn && exportOptions) {
            exportBtn.addEventListener('click', function() {
                exportOptions.style.display = exportOptions.style.display === 'none' ? 'block' : 'none';
            });
        }
        
        // Initialize table events on page load
        initializeTableEvents();
        
        // Clear search functionality
        const clearSearchBtn = document.createElement('button');
        clearSearchBtn.type = 'button';
        clearSearchBtn.className = 'btn btn-icon btn-active-light-primary w-30px h-30px position-absolute end-0 top-50 translate-middle-y me-3';
        clearSearchBtn.innerHTML = '<i class="ki-outline ki-cross fs-5"></i>';
        clearSearchBtn.style.display = 'none';
        
        searchInput.parentNode.appendChild(clearSearchBtn);
        
        searchInput.addEventListener('input', function() {
            clearSearchBtn.style.display = this.value ? 'block' : 'none';
        });
        
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearSearchBtn.style.display = 'none';
            performSearch();
        });
    });
</script>
@endpush