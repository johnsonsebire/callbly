@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <h1 class="h2 mb-4">Sender Name Management</h1>
                    </div>
                </div>

                <!--begin::Card-->
                <div class="card card-flush mb-5">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <h2 class="fw-bold">Sender Name List</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-2">
                                <!-- Add Sender Name Button for Users -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user_sender_name">
                                    <i class="ki-outline ki-plus fs-2"></i>
                                    Add Sender Name for User
                                </button>
                                
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-filter fs-2"></i>
                                    Filter
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">Filter Options</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->
                                    <!--begin::Content-->
                                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                                        <form id="filter-form" action="{{ route('admin.sender-names.index') }}" method="GET">
                                            <!--begin::Input group-->
                                            <div class="mb-10">
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <select class="form-select form-select-solid" name="status" data-control="select2" data-placeholder="Select status" data-allow-clear="true" data-kt-user-table-filter="status" data-hide-search="true">
                                                    <option value="">All</option>
                                                    <option value="pending" @if(request()->status == 'pending') selected @endif>Pending</option>
                                                    <option value="approved" @if(request()->status == 'approved') selected @endif>Approved</option>
                                                    <option value="rejected" @if(request()->status == 'rejected') selected @endif>Rejected</option>
                                                </select>
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Reset</button>
                                                <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Apply</button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Filter-->
                                
                                <!--begin::Search-->
                                <form action="{{ route('admin.sender-names.index') }}" method="GET" class="d-flex align-items-center">
                                    <div class="position-relative d-flex align-items-center">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                        <input type="text" name="search" class="form-control form-control-solid ps-12" 
                                            placeholder="Search Sender Names" value="{{ request()->search }}">
                                    </div>
                                    <button type="submit" class="btn btn-icon btn-light-primary ms-2">
                                        <i class="ki-outline ki-magnifier fs-2"></i>
                                    </button>
                                </form>
                                <!--end::Search-->
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->
                    
                    <!--begin::Card body-->
                    <div class="card-body py-4">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-success">Success</h4>
                                    <span>{{ session('success') }}</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-2 text-success"></i>
                                </button>
                            </div>
                        @endif
                        
                        @if(isset($senderNames) && $senderNames->isEmpty() && isset($pending) && $pending->isEmpty())
                            <div class="text-center p-20">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="ki-outline ki-abstract-26 fs-7x text-gray-300 mb-8"></i>
                                    <div class="text-gray-600 fs-3 fw-semibold mb-5">No sender names found</div>
                                    <div class="fs-5 text-muted mb-10">
                                        There are no sender names matching your search criteria.
                                    </div>
                                </div>
                            </div>
                        @else
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_sender_names">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">User</th>
                                        <th class="min-w-125px">Sender ID</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="min-w-125px">Requested At</th>
                                        <th class="min-w-125px">Approved/Rejected At</th>
                                        <th class="text-end min-w-100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @foreach(isset($senderNames) ? $senderNames : $pending as $item)
                                        <tr>
                                            <td class="d-flex align-items-center">
                                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                    <div class="symbol-label bg-light-{{ ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$item->status] }}">
                                                        <span class="text-{{ ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$item->status] }}">{{ substr($item->user->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800 text-hover-primary mb-1">{{ $item->user->name }}</span>
                                                    <span class="text-muted">{{ $item->user->email }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-primary fw-bold">{{ $item->name }}</span>
                                            </td>
                                            <td>
                                                @if($item->status == 'pending')
                                                    <div class="badge badge-light-warning">Pending</div>
                                                @elseif($item->status == 'approved')
                                                    <div class="badge badge-light-success">Approved</div>
                                                @else
                                                    <div class="badge badge-light-danger">Rejected</div>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                @if($item->status == 'approved' && $item->approved_at)
                                                    {{ $item->approved_at->format('M d, Y H:i') }}
                                                @elseif($item->status == 'rejected')
                                                    {{ $item->updated_at->format('M d, Y H:i') }}
                                                    <span class="ms-1 fs-7 text-muted" 
                                                          data-bs-toggle="tooltip" 
                                                          data-bs-placement="top" 
                                                          title="{{ $item->rejection_reason }}">
                                                        <i class="ki-outline ki-information-5 text-gray-600 fs-7"></i>
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($item->status == 'pending')
                                                    <div class="d-flex justify-content-end flex-shrink-0">
                                                        <form method="POST" action="{{ route('admin.sender-names.update', $item->id) }}" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn btn-icon btn-light-success btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve">
                                                                <i class="ki-outline ki-check fs-2"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#kt_modal_reject_{{ $item->id }}" 
                                                                data-bs-placement="top" title="Reject">
                                                            <i class="ki-outline ki-cross fs-2"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="d-flex justify-content-end flex-shrink-0">
                                                        <button type="button" class="btn btn-icon btn-light-primary btn-sm me-1" 
                                                              data-bs-toggle="modal" 
                                                              data-bs-target="#kt_modal_edit_{{ $item->id }}" 
                                                              data-bs-placement="top" title="Edit">
                                                            <i class="ki-outline ki-pencil fs-2"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-icon btn-light-danger btn-sm" 
                                                              data-bs-toggle="modal" 
                                                              data-bs-target="#kt_modal_delete_{{ $item->id }}" 
                                                              data-bs-placement="top" title="Delete">
                                                            <i class="ki-outline ki-trash fs-2"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        
                                        <!--begin::Modal - Edit Sender Name-->
                                        <div class="modal fade" tabindex="-1" id="kt_modal_edit_{{ $item->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.sender-names.update', $item->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="edit_mode" value="true">
                                                        
                                                        <div class="modal-header">
                                                            <h3 class="modal-title">Edit Sender Name</h3>
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="ki-outline ki-cross fs-1"></i>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="modal-body">
                                                            <div class="mb-5">
                                                                <p>You are editing the sender name for <strong>{{ $item->user->name }}</strong>.</p>
                                                            </div>
                                                            
                                                            <div class="fv-row mb-5">
                                                                <label for="edit_sender_name_{{ $item->id }}" class="required form-label">Sender Name</label>
                                                                <input type="text" class="form-control form-control-solid edit-sender-name" 
                                                                    id="edit_sender_name_{{ $item->id }}" 
                                                                    name="name" 
                                                                    value="{{ $item->name }}" 
                                                                    required 
                                                                    placeholder="Enter sender name (max 11 characters)" 
                                                                    maxlength="11">
                                                                <div class="form-text">Sender name can be up to 11 characters long, alphanumeric only. Case will be preserved exactly as entered.</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Modal - Edit Sender Name-->
                                        
                                        <!--begin::Modal - Reject Sender Name-->
                                        <div class="modal fade" tabindex="-1" id="kt_modal_reject_{{ $item->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.sender-names.update', $item->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        
                                                        <div class="modal-header">
                                                            <h3 class="modal-title">Reject Sender Name</h3>
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="ki-outline ki-cross fs-1"></i>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="modal-body">
                                                            <div class="mb-5">
                                                                <p>You are about to reject the sender name <strong>{{ $item->name }}</strong> requested by <strong>{{ $item->user->name }}</strong>.</p>
                                                                <p>Please provide a reason for rejection:</p>
                                                            </div>
                                                            
                                                            <div class="fv-row mb-5">
                                                                <textarea class="form-control form-control-solid" rows="4" name="rejection_reason" placeholder="Enter rejection reason" required></textarea>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Modal - Reject Sender Name-->
                                        
                                        <!--begin::Modal - Delete Sender Name-->
                                        <div class="modal fade" tabindex="-1" id="kt_modal_delete_{{ $item->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.sender-names.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                        <div class="modal-header">
                                                            <h3 class="modal-title">Delete Sender Name</h3>
                                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="ki-outline ki-cross fs-1"></i>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete the sender name <strong>{{ $item->name }}</strong>?</p>
                                                            <p class="text-danger">This action cannot be undone.</p>
                                                        </div>
                                                        
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Modal - Delete Sender Name-->
                                    @endforeach
                                </tbody>
                            </table>
                            <!--end::Table-->
                            
                            @if(isset($senderNames) && $senderNames instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="d-flex justify-content-end py-3">
                                    {{ $senderNames->appends(request()->except('page'))->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>

<!--begin::Modal - Add Sender Name for User-->
<div class="modal fade" tabindex="-1" id="kt_modal_add_user_sender_name">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.sender-names.create-for-user') }}">
                @csrf
                
                <div class="modal-header">
                    <h3 class="modal-title">Add Sender Name for User</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                
                <div class="modal-body">
                    <div class="mb-5">
                        <p>Create a new sender name on behalf of a user. This sender name will be automatically approved.</p>
                    </div>
                    
                    <div class="fv-row mb-5">
                        <label for="user_id" class="required form-label">Select User</label>
                        <select class="form-select form-select-solid" id="user_id" name="user_id" required data-control="select2" data-placeholder="Select a user" data-dropdown-parent="#kt_modal_add_user_sender_name">
                            <option></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="fv-row mb-5">
                        <label for="sender_name" class="required form-label">Sender Name</label>
                        <input type="text" class="form-control form-control-solid" id="sender_name" name="name" required 
                            placeholder="Enter sender name (max 11 characters)" maxlength="11">
                        <div class="form-text">Sender name can be up to 11 characters long, alphanumeric only. Case will be preserved exactly as entered.</div>
                    </div>
                    
                    <div class="form-check form-switch form-check-custom form-check-solid mb-5">
                        <input class="form-check-input" type="checkbox" id="auto_approve" name="auto_approve" value="1" checked/>
                        <label class="form-check-label" for="auto_approve">
                            Automatically approve this sender name
                        </label>
                    </div>
                    
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                        <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <h4 class="text-gray-900 fw-bold">Note</h4>
                                <div class="fs-6 text-gray-700">This sender name will be added to the selected user's account and will be immediately available for them to use if auto-approved.</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Sender Name</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal - Add Sender Name for User-->

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Reset filter form
    document.querySelector('[data-kt-user-table-filter="reset"]')?.addEventListener('click', function() {
        document.querySelector('#filter-form').reset();
        window.location.href = "{{ route('admin.sender-names.index') }}";
    });
    
    // Validate sender name input for creation and editing
    document.getElementById('sender_name')?.addEventListener('input', function() {
        this.value = this.value.replace(/[^A-Za-z0-9]/g, '').substr(0, 11);
    });
    
    // Validate sender name input for editing
    document.querySelectorAll('.edit-sender-name').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z0-9]/g, '').substr(0, 11);
        });
    });
</script>
@endpush
@endsection
