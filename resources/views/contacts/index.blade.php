@extends('layouts.master')

@section('title', 'Audience Contacts Directory')

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
                                        <i class="ki-outline ki-profile-user fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Audience Contacts Directory</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Audience Manager</span>
                                    </div>
                                    <div class="d-flex flex-wrap align-items-center gap-4 text-gray-400 fs-7 mt-2">
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-people fs-5 me-1 text-primary"></i>
                                            Total Contacts: <strong class="text-white ms-1 me-2">{{ number_format($contacts->total()) }}</strong>
                                        </span>
                                        <span>•</span>
                                        <span class="d-flex align-items-center">
                                            <i class="ki-outline ki-category fs-5 me-1 text-success"></i>
                                            Groups: <strong class="text-white ms-1">{{ $groups->count() }} Saved Segments</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('contacts.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Add Contact
                                </a>
                                <a href="{{ route('contacts.import') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-cloud-add me-1 fs-5"></i> Import CSV
                                </a>
                                <a href="{{ route('contacts.export') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-exit-down me-1 fs-5"></i> Export
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacts Container Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Contact Database</span>
                            <span class="text-gray-400 mt-1 fs-7">Search, filter, and dispatch SMS to target customer groups</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Search & Filter Controls Row -->
                        <div class="row g-4 mb-6 align-items-center">
                            <!-- Search -->
                            <div class="col-lg-6 col-md-8">
                                <div class="d-flex align-items-center position-relative search-container">
                                    <i class="ki-outline ki-magnifier fs-2 position-absolute ms-4 text-gray-400"></i>
                                    <input type="text" 
                                           data-kt-contacts-table-filter="search" 
                                           class="form-control form-control-solid ps-12 pe-12 rounded-3" 
                                           placeholder="Search contacts by name, phone, email, company..." 
                                           id="contactSearch" />
                                    <button type="button" class="search-clear-btn me-2" id="clearSearchBtn">
                                        <i class="ki-outline ki-cross fs-5"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Group Filter & Export Toggle -->
                            <div class="col-lg-6 col-md-4">
                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <select class="form-select form-select-solid rounded-3" 
                                            data-control="select2" 
                                            data-placeholder="Filter by group" 
                                            id="groupFilter" 
                                            style="min-width: 180px;">
                                        <option value="all">All Contact Groups</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <button type="button" 
                                            class="btn btn-light-primary font-bold flex-shrink-0 rounded-3 shadow-xs" 
                                            id="exportContactsBtn">
                                        <i class="ki-outline ki-setting-3 fs-3 me-1"></i> Export Options
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Export Options Panel -->
                        <div id="exportOptions" class="mb-6" style="display: none;">
                            <div class="card border border-gray-200 shadow-sm rounded-3">
                                <div class="card-header bg-light-soft pt-4 pb-3">
                                    <div class="card-title">
                                        <i class="ki-outline ki-exit-down fs-3 text-primary me-2"></i>
                                        <span class="font-bold text-gray-900 fs-5">Export Contacts Configuration</span>
                                    </div>
                                </div>
                                <div class="card-body pt-4">
                                    <form action="{{ route('contacts.export') }}" method="GET">
                                        <div class="row g-4 align-items-end">
                                            <div class="col-lg-4 col-md-6">
                                                <label class="form-label font-bold text-gray-800 fs-7 mb-3">Export Selection Scope</label>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="export_type" id="exportAll" value="all" checked>
                                                    <label class="form-check-label font-bold text-gray-800 fs-7" for="exportAll">
                                                        Export all contacts in database
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="export_type" id="exportSelected" value="selected">
                                                    <label class="form-check-label font-bold text-gray-800 fs-7" for="exportSelected">
                                                        Export selected contacts only
                                                    </label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4 col-md-6">
                                                <label for="exportGroupFilter" class="form-label font-bold text-gray-800 fs-7">Group Segment Filter</label>
                                                <select class="form-select form-select-solid" name="group_id" id="exportGroupFilter">
                                                    <option value="">All groups</option>
                                                    @foreach($groups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-lg-4 col-md-12">
                                                <button type="submit" class="btn btn-primary font-bold w-100 py-3 shadow-xs">
                                                    <i class="ki-outline ki-exit-down fs-4 me-2"></i>Download Export File
                                                </button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="selected_contacts" id="selectedContactIds" value="">
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Contacts Table Container -->
                        <div class="position-relative">
                            <div id="contactsLoading" class="d-none">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading contacts...</span>
                                </div>
                                <div class="ms-3 font-semibold text-gray-600">Searching contacts...</div>
                            </div>
                            
                            <div id="contactsTableContainer">
                                @include('contacts.partials.contacts_table')
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
    
    #contactsLoading {
        background: rgba(255, 255, 255, 0.85);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
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
        
        function performSearch() {
            const searchTerm = searchInput.value.trim();
            const selectedGroup = groupFilter.value;
            
            loadingIndicator.classList.remove('d-none');
            contactsTableContainer.style.opacity = '0.6';
            contactsTableContainer.style.pointerEvents = 'none';
            
            const url = new URL('{{ route("contacts.search") }}', window.location.origin);
            url.searchParams.append('q', searchTerm);
            if (selectedGroup && selectedGroup !== 'all') {
                url.searchParams.append('group', selectedGroup);
            }
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                contactsTableContainer.innerHTML = html;
                loadingIndicator.classList.add('d-none');
                contactsTableContainer.style.opacity = '1';
                contactsTableContainer.style.pointerEvents = 'auto';
                
                initializeTableEvents();
                initializeTooltips();
                
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
                
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger';
                errorDiv.innerHTML = '<i class="ki-outline ki-information-5 fs-3 me-2"></i>Failed to load contacts. Please try again.';
                contactsTableContainer.innerHTML = '';
                contactsTableContainer.appendChild(errorDiv);
            });
        }
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        });
        
        function initializeGroupFilter() {
            if (!groupFilter) return;
            groupFilter.addEventListener('change', function() {
                performSearch();
            });
            
            if (groupFilter.hasAttribute('data-control') && groupFilter.getAttribute('data-control') === 'select2') {
                const attachSelect2Listener = () => {
                    if (typeof $ !== 'undefined' && $(groupFilter).hasClass('select2-hidden-accessible')) {
                        $(groupFilter).off('change.contactsFilter');
                        $(groupFilter).on('change.contactsFilter', function() {
                            performSearch();
                        });
                        return true;
                    }
                    return false;
                };
                if (!attachSelect2Listener()) {
                    setTimeout(attachSelect2Listener, 100);
                    setTimeout(attachSelect2Listener, 500);
                }
            }
        }
        
        initializeGroupFilter();
        
        function initializeTableEvents() {
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
                    if (selectAllCheckbox) {
                        const allChecked = Array.from(contactCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(contactCheckboxes).some(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    }
                });
            });
        }
        
        function updateSelectedContactsInput() {
            const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
            const selectedIds = Array.from(contactCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            
            const selectedContactIdsInput = document.getElementById('selectedContactIds');
            if (selectedContactIdsInput) {
                selectedContactIdsInput.value = selectedIds.join(',');
            }
            
            const exportSelected = document.getElementById('exportSelected');
            const exportAll = document.getElementById('exportAll');
            if (selectedIds.length > 0 && exportSelected) {
                exportSelected.checked = true;
            } else if (exportAll) {
                exportAll.checked = true;
            }
        }
        
        const exportBtn = document.getElementById('exportContactsBtn');
        const exportOptions = document.getElementById('exportOptions');
        
        if (exportBtn && exportOptions) {
            exportBtn.addEventListener('click', function() {
                if (exportOptions.style.display === 'none' || !exportOptions.style.display) {
                    exportOptions.style.display = 'block';
                    exportOptions.style.opacity = '0';
                    exportOptions.style.transform = 'translateY(-10px)';
                    
                    requestAnimationFrame(() => {
                        exportOptions.style.transition = 'all 0.3s ease-in-out';
                        exportOptions.style.opacity = '1';
                        exportOptions.style.transform = 'translateY(0)';
                    });
                    
                    exportBtn.innerHTML = '<i class="ki-outline ki-cross fs-3 me-1"></i> Close Options';
                    exportBtn.classList.remove('btn-light-primary');
                    exportBtn.classList.add('btn-light-danger');
                } else {
                    exportOptions.style.transition = 'all 0.3s ease-in-out';
                    exportOptions.style.opacity = '0';
                    exportOptions.style.transform = 'translateY(-10px)';
                    
                    setTimeout(() => {
                        exportOptions.style.display = 'none';
                    }, 300);
                    
                    exportBtn.innerHTML = '<i class="ki-outline ki-setting-3 fs-3 me-1"></i> Export Options';
                    exportBtn.classList.remove('btn-light-danger');
                    exportBtn.classList.add('btn-light-primary');
                }
            });
        }
        
        initializeTableEvents();
        
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        function toggleClearButton() {
            if (searchInput.value.trim()) {
                clearSearchBtn.classList.add('show');
            } else {
                clearSearchBtn.classList.remove('show');
            }
        }
        
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
        
        toggleClearButton();
        
        window.clearFilters = function() {
            searchInput.value = '';
            if (groupFilter) {
                groupFilter.value = 'all';
                if (typeof $ !== 'undefined' && $(groupFilter).hasClass('select2-hidden-accessible')) {
                    $(groupFilter).val('all').trigger('change');
                }
            }
            toggleClearButton();
            performSearch();
        };
        
        function initializeTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        
        initializeTooltips();
    });
</script>
@endpush