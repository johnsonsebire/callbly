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
                        <div class="card-toolbar d-flex flex-wrap gap-2">
                            <a href="{{ route('contacts.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-plus-square fs-2 me-2"></i>Add Contact
                            </a>
                            <a href="{{ route('contacts.import') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-cloud-add fs-2 me-2"></i>Import
                            </a>
                            <a href="{{ route('contacts.export') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-exit-down fs-2 me-2"></i>Export
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
                        <div class="row g-4 mb-6">
                            <!-- Search Section -->
                            <div class="col-lg-6 col-md-8">
                                <div class="d-flex align-items-center position-relative search-container">
                                    <i class="ki-outline ki-magnifier fs-1 position-absolute ms-4 text-gray-500"></i>
                                    <input type="text" 
                                           data-kt-contacts-table-filter="search" 
                                           class="form-control form-control-solid ps-15 pe-12" 
                                           placeholder="Search contacts by name, phone, email..." 
                                           id="contactSearch" />
                                    <button type="button" class="search-clear-btn" id="clearSearchBtn">
                                        <i class="ki-outline ki-cross fs-5"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Filter and Actions Section -->
                            <div class="col-lg-6 col-md-4">
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <select class="form-select form-select-solid" 
                                            data-control="select2" 
                                            data-placeholder="Filter by group" 
                                            id="groupFilter" 
                                            style="min-width: 180px;">
                                        <option value="all">All Groups</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <button type="button" 
                                            class="btn btn-light-primary flex-shrink-0" 
                                            id="exportContactsBtn">
                                        <i class="ki-outline ki-setting-3 fs-2 me-2"></i>Export Options
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Export Options Panel -->
                        <div id="exportOptions" class="mb-6" style="display: none;">
                            <div class="card border border-gray-300 shadow-sm">
                                <div class="card-header bg-light-primary">
                                    <div class="card-title">
                                        <i class="ki-outline ki-exit-down fs-2 text-primary me-2"></i>
                                        <span class="fw-bold text-primary">Export Contacts</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('contacts.export') }}" method="GET">
                                        <div class="row g-4">
                                            <!-- Export Type Selection -->
                                            <div class="col-lg-4 col-md-6">
                                                <h6 class="fw-bold text-gray-800 mb-3">
                                                    <i class="ki-outline ki-check-square fs-3 me-2"></i>Export Type
                                                </h6>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="export_type" id="exportAll" value="all" checked>
                                                    <label class="form-check-label fw-semibold" for="exportAll">
                                                        <i class="ki-outline ki-people fs-4 me-1 text-primary"></i>
                                                        Export all contacts
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="export_type" id="exportSelected" value="selected">
                                                    <label class="form-check-label fw-semibold" for="exportSelected">
                                                        <i class="ki-outline ki-click fs-4 me-1 text-warning"></i>
                                                        Export selected contacts
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <!-- Group Filter -->
                                            <div class="col-lg-4 col-md-6">
                                                <h6 class="fw-bold text-gray-800 mb-3">
                                                    <i class="ki-outline ki-filter fs-3 me-2"></i>Group Filter
                                                </h6>
                                                <select class="form-select form-select-solid" name="group_id" id="exportGroupFilter">
                                                    <option value="">All groups</option>
                                                    @foreach($groups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text text-muted mt-2">
                                                    <i class="ki-outline ki-information-5 fs-6 me-1"></i>
                                                    Optional: Filter by specific group
                                                </div>
                                            </div>
                                            
                                            <!-- Action Button -->
                                            <div class="col-lg-4 col-md-12">
                                                <h6 class="fw-bold text-gray-800 mb-3">
                                                    <i class="ki-outline ki-rocket fs-3 me-2"></i>Action
                                                </h6>
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="ki-outline ki-exit-down fs-2 me-2"></i>
                                                    Export Contacts Now
                                                </button>
                                                <div class="form-text text-muted mt-2 text-center">
                                                    <i class="ki-outline ki-file fs-6 me-1"></i>
                                                    Downloads as Excel file
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="selected_contacts" id="selectedContactIds" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contacts Table Container -->
                        <div class="position-relative">
                            <!-- Loading indicator -->
                            <div id="contactsLoading" class="d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading contacts...</span>
                                </div>
                                <div class="ms-3 fw-semibold text-gray-600">Searching contacts...</div>
                            </div>
                            
                            <div id="contactsTableContainer">
                                @include('contacts.partials.contacts_table')
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

@push('styles')
<style>
    /* Responsive improvements */
    @media (max-width: 768px) {
        .card-toolbar {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .card-toolbar .btn {
            width: 100%;
            justify-content: center;
        }
        
        #exportContactsBtn {
            white-space: nowrap;
        }
    }
    
    @media (max-width: 576px) {
        .row.g-4.mb-6 {
            flex-direction: column;
        }
        
        .row.g-4.mb-6 .col-lg-6 {
            width: 100%;
        }
        
        .d-flex.align-items-center.justify-content-end {
            justify-content: stretch !important;
            flex-direction: column;
            gap: 1rem;
        }
        
        #groupFilter {
            min-width: 100% !important;
        }
        
        #exportContactsBtn {
            width: 100%;
        }
    }
    
    /* Search input enhancements */
    .search-container {
        position: relative;
    }
    
    .search-clear-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: none;
        border: none;
        color: #7e8299;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        opacity: 0;
        transition: all 0.2s ease;
    }
    
    .search-clear-btn:hover {
        background-color: #f5f5f5;
        color: #3f4254;
    }
    
    .search-clear-btn.show {
        opacity: 1;
    }
    
    /* Export options animation */
    #exportOptions {
        transition: all 0.3s ease-in-out;
    }
    
    /* Button improvements */
    .btn.flex-shrink-0 {
        white-space: nowrap;
    }
    
    /* Loading state improvements */
    #contactsLoading {
        background: rgba(255, 255, 255, 0.8);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

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
            contactsTableContainer.style.opacity = '0.6';
            contactsTableContainer.style.pointerEvents = 'none';
            
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
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                contactsTableContainer.innerHTML = html;
                loadingIndicator.classList.add('d-none');
                contactsTableContainer.style.opacity = '1';
                contactsTableContainer.style.pointerEvents = 'auto';
                
                // Reinitialize event listeners for the new content
                initializeTableEvents();
                
                // Update URL without page reload (for better UX)
                const newUrl = new URL(window.location);
                if (searchTerm) {
                    newUrl.searchParams.set('search', searchTerm);
                } else {
                    newUrl.searchParams.delete('search');
                }
                if (selectedGroup && selectedGroup !== 'all') {
                    newUrl.searchParams.set('group', selectedGroup);
                } else {
                    newUrl.searchParams.delete('group');
                }
                window.history.replaceState({}, '', newUrl);
            })
            .catch(error => {
                console.error('Search error:', error);
                loadingIndicator.classList.add('d-none');
                contactsTableContainer.style.opacity = '1';
                contactsTableContainer.style.pointerEvents = 'auto';
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger';
                errorDiv.innerHTML = '<i class="ki-outline ki-information-5 fs-3 me-2"></i>Failed to load contacts. Please try again.';
                contactsTableContainer.innerHTML = '';
                contactsTableContainer.appendChild(errorDiv);
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
        
        // Toggle export options panel with smooth animation
        const exportBtn = document.getElementById('exportContactsBtn');
        const exportOptions = document.getElementById('exportOptions');
        
        if (exportBtn && exportOptions) {
            exportBtn.addEventListener('click', function() {
                if (exportOptions.style.display === 'none' || !exportOptions.style.display) {
                    exportOptions.style.display = 'block';
                    exportOptions.style.opacity = '0';
                    exportOptions.style.transform = 'translateY(-10px)';
                    
                    // Smooth fade in animation
                    requestAnimationFrame(() => {
                        exportOptions.style.transition = 'all 0.3s ease-in-out';
                        exportOptions.style.opacity = '1';
                        exportOptions.style.transform = 'translateY(0)';
                    });
                    
                    // Update button text and icon
                    exportBtn.innerHTML = '<i class="ki-outline ki-cross fs-2 me-2"></i>Close Options';
                    exportBtn.classList.remove('btn-light-primary');
                    exportBtn.classList.add('btn-light-danger');
                } else {
                    // Smooth fade out animation
                    exportOptions.style.transition = 'all 0.3s ease-in-out';
                    exportOptions.style.opacity = '0';
                    exportOptions.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        exportOptions.style.display = 'none';
                    }, 300);
                    
                    // Reset button text and icon
                    exportBtn.innerHTML = '<i class="ki-outline ki-setting-3 fs-2 me-2"></i>Export Options';
                    exportBtn.classList.remove('btn-light-danger');
                    exportBtn.classList.add('btn-light-primary');
                }
            });
        }
        
        // Initialize table events on page load
        initializeTableEvents();
        
        // Enhanced clear search functionality
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        
        // Show/hide clear button based on input content
        function toggleClearButton() {
            if (searchInput.value.trim()) {
                clearSearchBtn.classList.add('show');
            } else {
                clearSearchBtn.classList.remove('show');
            }
        }
        
        // Search input event listeners
        searchInput.addEventListener('input', function() {
            toggleClearButton();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        });
        
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            toggleClearButton();
            searchInput.focus();
            performSearch();
        });
        
        // Initialize clear button state
        toggleClearButton();
    });
</script>
@endpush