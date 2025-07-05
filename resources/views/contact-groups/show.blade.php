@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Group Details Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">{{ $group->name }}</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Group details and contacts</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contact-groups.edit', $group) }}" class="btn btn-sm btn-light-primary me-2">
                                <i class="ki-outline ki-pencil fs-2 me-2"></i>Edit Group
                            </a>
                            <a href="{{ route('contact-groups.index') }}" class="btn btn-sm btn-light-primary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i>Back to Groups
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        <!-- Group Info Section -->
                        <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                            <div class="me-7 mb-4">
                                <div class="symbol symbol-100px symbol-lg-120px symbol-fixed position-relative">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-people-circle fs-2tx text-primary"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="text-gray-900 fs-2 fw-bold me-1">{{ $group->name }}</span>
                                            <span class="badge badge-light-primary fs-7 fw-semibold ms-2">{{ $contacts->total() }} contacts</span>
                                        </div>
                                        
                                        @if($group->description)
                                        <div class="fs-6 text-muted mb-3">{{ $group->description }}</div>
                                        @endif
                                        
                                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                            <span class="d-flex align-items-center text-muted me-5 mb-2">
                                                <i class="ki-outline ki-calendar fs-4 me-1"></i>
                                                Created on {{ $group->created_at->format('M d, Y') }}
                                            </span>
                                            <span class="d-flex align-items-center text-muted mb-2">
                                                <i class="ki-outline ki-calendar-8 fs-4 me-1"></i>
                                                Updated {{ $group->updated_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex flex-wrap flex-stack gap-2 mt-3">
                                    <a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addContactsModal">
                                        <i class="ki-outline ki-plus-square fs-2 me-2"></i>Add Contacts
                                    </a>
                                    <a href="{{ route('sms.compose') }}?group_id={{ $group->id }}" class="btn btn-sm btn-success me-2">
                                        <i class="ki-outline ki-message-text-2 fs-2 me-2"></i>Send SMS to Group
                                    </a>
                                    <a href="{{ route('contacts.export') }}?group_id={{ $group->id }}" class="btn btn-sm btn-light-primary me-2">
                                        <i class="ki-outline ki-file-down fs-2 me-2"></i>Export Contacts
                                    </a>
                                    <form action="{{ route('contact-groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this group? This will not delete the contacts.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light-danger">
                                            <i class="ki-outline ki-trash fs-2 me-2"></i>Delete Group
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="separator separator-dashed my-6"></div>
                        
                        <!-- Contacts Tab Section -->
                        <div class="d-flex flex-column mt-5">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h3 class="fw-bold text-dark mb-0">Contacts in this group</h3>
                                <span class="badge badge-light-primary fs-6" id="contactsCount">{{ $contacts->total() }} contacts</span>
                            </div>
                            
                            <!-- Search and Filter Section -->
                            <div class="card card-flush mb-5">
                                <div class="card-body py-5">
                                    <div class="row g-3">
                                        <!-- Search Input -->
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center position-relative">
                                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                                <input type="text" id="contactSearch" class="form-control form-control-solid ps-12" placeholder="Search contacts by name, phone, email, or company..." autocomplete="off">
                                                <button type="button" id="clearSearch" class="btn btn-sm btn-icon btn-light position-absolute end-0 me-2" style="display: none;">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Sort Options -->
                                        <div class="col-md-3">
                                            <select id="sortBy" class="form-select form-select-solid">
                                                <option value="first_name">Sort by First Name</option>
                                                <option value="last_name">Sort by Last Name</option>
                                                <option value="phone_number">Sort by Phone</option>
                                                <option value="email">Sort by Email</option>
                                                <option value="company">Sort by Company</option>
                                                <option value="created_at">Sort by Date Added</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Sort Direction -->
                                        <div class="col-md-3">
                                            <select id="sortDirection" class="form-select form-select-solid">
                                                <option value="asc">Ascending</option>
                                                <option value="desc">Descending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Loading Indicator -->
                            <div id="loadingIndicator" class="text-center py-5" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <div class="fs-6 text-muted mt-2">Searching contacts...</div>
                            </div>
                            
                            <!-- Contacts Table -->
                            <div id="contactsTableContainer">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="contactsTable">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th class="min-w-150px">Name</th>
                                                <th class="min-w-150px">Phone Number</th>
                                                <th class="min-w-150px">Email</th>
                                                <th class="min-w-100px text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="contactsTableBody">
                                            @forelse($contacts as $contact)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-45px me-3">
                                                                <span class="symbol-label bg-light-primary text-primary">
                                                                    {{ substr($contact->full_name, 0, 1) }}
                                                                </span>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a href="{{ route('contacts.show', $contact) }}" class="text-dark text-hover-primary fw-bold">{{ $contact->full_name }}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $contact->phone_number }}</td>
                                                    <td>{{ $contact->email ?: 'N/A' }}</td>
                                                    <td class="text-end">
                                                        <form action="{{ route('contact-groups.remove-contact', [$group->id, $contact->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('Remove this contact from the group?')">
                                                                <i class="ki-outline ki-minus-square fs-2"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr id="noContactsRow">
                                                    <td colspan="4" class="text-center py-10">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="ki-outline ki-people fs-2tx text-gray-300 mb-5"></i>
                                                            <span class="text-gray-600 fs-5 fw-semibold">No contacts in this group yet</span>
                                                            <span class="text-gray-400 fs-7">Add contacts to get started</span>
                                                            <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addContactsModal">
                                                                <i class="ki-outline ki-plus-square fs-2 me-2"></i>Add Your First Contact
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination Container -->
                                <div class="d-flex justify-content-center mt-4" id="paginationContainer">
                                    {{ $contacts->links() }}
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

<!-- Add Contacts Modal -->
<div class="modal fade" id="addContactsModal" tabindex="-1" aria-labelledby="addContactsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fw-bold" id="addContactsModalLabel">Add Contacts to Group</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('contact-groups.store-contacts', $group->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fs-6 text-gray-600">Select contacts to add to <strong>{{ $group->name }}</strong>.</p>
                    
                    <!-- To be implemented: a livewire or AJAX component that loads contacts not in the group yet -->
                    <div class="alert alert-info d-flex align-items-center p-5 mb-6">
                        <i class="ki-outline ki-information-5 fs-2 me-4 text-info"></i>
                        <div class="d-flex flex-column">
                            <div>To add contacts to this group, you need to select them from your contact list first.</div>
                            <div><a href="{{ route('contacts.index') }}" class="fw-bold text-hover-primary">Go to Contacts</a></div>
                        </div>
                    </div>
                    
                    <div class="form-check form-check-custom form-check-solid mt-5">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label fw-semibold text-gray-700" for="select-all">
                            Select All
                        </label>
                    </div>
                    
                    <div class="contact-list mt-5" style="max-height: 300px; overflow-y: auto;">
                        <!-- This would be populated via AJAX/Livewire -->
                        <div class="alert alert-warning d-flex align-items-center p-5">
                            <i class="ki-outline ki-information-5 fs-2 me-4 text-warning"></i>
                            <div class="d-flex flex-column">
                                <div>Contact selection feature will be implemented with AJAX soon.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2 me-2"></i>Add Selected Contacts
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const contactSearchInput = document.getElementById('contactSearch');
        const clearSearchBtn = document.getElementById('clearSearch');
        const sortBySelect = document.getElementById('sortBy');
        const sortDirectionSelect = document.getElementById('sortDirection');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const contactsTableContainer = document.getElementById('contactsTableContainer');
        const contactsTableBody = document.getElementById('contactsTableBody');
        const contactsCount = document.getElementById('contactsCount');
        const paginationContainer = document.getElementById('paginationContainer');
        const selectAllCheckbox = document.getElementById('select-all');
        
        // Configuration
        const groupId = {{ $group->id }};
        const searchUrl = "{{ route('contact-groups.search-contacts', $group->id) }}";
        let searchTimeout;
        let currentRequest;
        
        // Initialize
        updateClearButtonVisibility();
        
        // Search functionality
        function performSearch() {
            // Cancel previous request
            if (currentRequest) {
                currentRequest.abort();
            }
            
            const searchTerm = contactSearchInput.value.trim();
            const sortBy = sortBySelect.value;
            const sortDirection = sortDirectionSelect.value;
            
            // Build query parameters
            const params = new URLSearchParams({
                search: searchTerm,
                sort_by: sortBy,
                sort_direction: sortDirection
            });
            
            // Show loading indicator
            showLoading();
            
            // Make AJAX request
            currentRequest = fetch(`${searchUrl}?${params}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateContactsTable(data.data);
                    updateContactsCount(data.data.total);
                    updatePagination(data.data.pagination_links);
                } else {
                    throw new Error(data.message || 'Search failed');
                }
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Search error:', error);
                    showError('An error occurred while searching contacts.');
                }
            })
            .finally(() => {
                hideLoading();
                currentRequest = null;
            });
        }
        
        function updateContactsTable(data) {
            if (data.contacts.length === 0) {
                contactsTableBody.innerHTML = `
                    <tr id="noContactsRow">
                        <td colspan="4" class="text-center py-10">
                            <div class="d-flex flex-column align-items-center">
                                <i class="ki-outline ki-magnifier fs-2tx text-gray-300 mb-5"></i>
                                <span class="text-gray-600 fs-5 fw-semibold">No contacts found</span>
                                <span class="text-gray-400 fs-7">Try adjusting your search criteria</span>
                            </div>
                        </td>
                    </tr>
                `;
            } else {
                contactsTableBody.innerHTML = data.contacts.map(contact => `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px me-3">
                                    <span class="symbol-label bg-light-primary text-primary">
                                        ${contact.first_name.charAt(0).toUpperCase()}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="/contacts/${contact.id}" class="text-dark text-hover-primary fw-bold">
                                        ${contact.first_name} ${contact.last_name}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>${contact.phone_number}</td>
                        <td>${contact.email || 'N/A'}</td>
                        <td class="text-end">
                            <form action="/contact-groups/${groupId}/contacts/${contact.id}" method="POST" class="d-inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-light-danger" onclick="return confirm('Remove this contact from the group?')">
                                    <i class="ki-outline ki-minus-square fs-2"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `.replace('__CONTACT_ID__', contact.id)).join('');
            }
        }
        
        function updateContactsCount(total) {
            contactsCount.textContent = `${total} contact${total !== 1 ? 's' : ''}`;
        }
        
        function updatePagination(paginationHtml) {
            paginationContainer.innerHTML = paginationHtml;
            
            // Re-attach click events to pagination links
            paginationContainer.querySelectorAll('a[href*="page="]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page');
                    
                    // Add current search params to pagination request
                    const params = new URLSearchParams({
                        search: contactSearchInput.value.trim(),
                        sort_by: sortBySelect.value,
                        sort_direction: sortDirectionSelect.value,
                        page: page
                    });
                    
                    showLoading();
                    fetch(`${searchUrl}?${params}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateContactsTable(data.data);
                            updateContactsCount(data.data.total);
                            updatePagination(data.data.pagination_links);
                        }
                    })
                    .catch(error => {
                        console.error('Pagination error:', error);
                        showError('An error occurred while loading contacts.');
                    })
                    .finally(() => {
                        hideLoading();
                    });
                });
            });
        }
        
        function showLoading() {
            loadingIndicator.style.display = 'block';
            contactsTableContainer.style.opacity = '0.5';
        }
        
        function hideLoading() {
            loadingIndicator.style.display = 'none';
            contactsTableContainer.style.opacity = '1';
        }
        
        function showError(message) {
            // Create and show error alert
            const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ki-outline ki-cross-circle fs-2 me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            
            const alertContainer = document.createElement('div');
            alertContainer.innerHTML = alertHtml;
            contactsTableContainer.parentNode.insertBefore(alertContainer.firstElementChild, contactsTableContainer);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert-danger');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
        
        function updateClearButtonVisibility() {
            if (contactSearchInput.value.trim()) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }
        }
        
        // Event Listeners
        contactSearchInput.addEventListener('input', function() {
            updateClearButtonVisibility();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout for debounced search
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 300); // 300ms delay
        });
        
        clearSearchBtn.addEventListener('click', function() {
            contactSearchInput.value = '';
            updateClearButtonVisibility();
            performSearch();
        });
        
        sortBySelect.addEventListener('change', performSearch);
        sortDirectionSelect.addEventListener('change', performSearch);
        
        // Handle Enter key in search input
        contactSearchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                performSearch();
            }
        });
        
        // Select all checkbox functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.contact-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }
    });
</script>
@endpush