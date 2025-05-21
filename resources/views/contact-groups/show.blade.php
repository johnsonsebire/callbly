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
                            <h3 class="fw-bold text-dark mb-5">Contacts in this group</h3>
                            
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-150px">Phone Number</th>
                                            <th class="min-w-150px">Email</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                            <tr>
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
                            
                            <div class="d-flex justify-content-center mt-4">
                                {{ $contacts->links() }}
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
    // Select all checkbox functionality
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
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