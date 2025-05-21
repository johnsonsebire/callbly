@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <div class="card-title">
                            <h2 class="fw-bold">Users Management</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>New User
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
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

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">Error</h4>
                                    <span>{{ session('error') }}</span>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-2 text-danger"></i>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">User</th>
                                        <th class="min-w-125px">Role</th>
                                        <th class="min-w-125px">SMS Credits</th>
                                        <th class="min-w-125px">Status</th>
                                        <th class="text-end min-w-100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @foreach($users as $user)
                                    <tr>
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                @if($user->profile_photo_url)
                                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                                @else
                                                    <div class="symbol-label bg-light-primary">
                                                        <span class="text-primary">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="text-gray-800 text-hover-primary mb-1">{{ $user->name }}</a>
                                                <span class="text-muted">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-light-primary fs-7 m-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ number_format($user->sms_credits ?? 0) }}</span>
                                                @if(!$user->hasRole('super-admin'))
                                                <button type="button" class="btn btn-sm btn-icon btn-light-success" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#kt_modal_topup_credits_{{ $user->id }}" 
                                                        title="Add SMS Credits">
                                                    <i class="ki-outline ki-plus fs-2"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <div class="badge badge-light-success">Verified</div>
                                            @else
                                                <div class="badge badge-light-warning">Pending</div>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                <button type="button" class="btn btn-icon btn-light-primary btn-sm me-1" 
                                                      data-bs-toggle="modal" 
                                                      data-bs-target="#kt_modal_assign_role_{{ $user->id }}">
                                                    <i class="ki-outline ki-shield-tick fs-2"></i>
                                                </button>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon btn-light-primary btn-sm me-1">
                                                    <i class="ki-outline ki-pencil fs-2"></i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-light-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="ki-outline ki-trash fs-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!--begin::Modal - Assign Role-->
                                    <div class="modal fade" tabindex="-1" id="kt_modal_assign_role_{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.users.update-role', $user->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Assign Role to {{ $user->name }}</h3>
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal-body">
                                                        <div class="fv-row">
                                                            <label class="form-label">Select Role</label>
                                                            
                                                            @foreach($roles as $role)
                                                            <div class="form-check form-check-custom form-check-solid mb-5">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="roles[]" 
                                                                       value="{{ $role->id }}"
                                                                       id="role_{{ $user->id }}_{{ $role->id }}"
                                                                       {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="role_{{ $user->id }}_{{ $role->id }}">
                                                                    {{ $role->name }}
                                                                </label>
                                                            </div>
                                                            @endforeach
                                                            
                                                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6 mt-4">
                                                                <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                                                                <div class="d-flex flex-stack flex-grow-1">
                                                                    <div class="fw-semibold">
                                                                        <h4 class="text-gray-900 fw-bold">Important Note</h4>
                                                                        <div class="fs-6 text-gray-700">Changing user roles will affect their permission levels in the system.</div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                    <!--end::Modal - Assign Role-->
                                    
                                    <!--begin::Modal - Add SMS Credits-->
                                    <div class="modal fade" tabindex="-1" id="kt_modal_topup_credits_{{ $user->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.users.add-credits', $user->id) }}">
                                                    @csrf
                                                    
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Add SMS Credits for {{ $user->name }}</h3>
                                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="ki-outline ki-cross fs-1"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal-body">
                                                        <div class="mb-5">
                                                            <p>Current SMS Credits: <strong>{{ number_format($user->sms_credits ?? 0) }}</strong></p>
                                                        </div>
                                                        
                                                        <div class="fv-row mb-5">
                                                            <label for="credits_amount_{{ $user->id }}" class="required form-label">Credits to Add</label>
                                                            <input type="number" class="form-control form-control-solid" 
                                                                id="credits_amount_{{ $user->id }}" 
                                                                name="credits" 
                                                                min="1"
                                                                required placeholder="Enter number of credits">
                                                        </div>
                                                        
                                                        <div class="fv-row mb-5">
                                                            <label for="credits_note_{{ $user->id }}" class="form-label">Note (Optional)</label>
                                                            <textarea class="form-control form-control-solid" 
                                                                id="credits_note_{{ $user->id }}" 
                                                                name="note" 
                                                                rows="3"
                                                                placeholder="Add a note about this credit addition"></textarea>
                                                        </div>
                                                        
                                                        <div class="notice d-flex bg-light-info rounded border-info border border-dashed p-6">
                                                            <i class="ki-outline ki-information-5 fs-2tx text-info me-4"></i>
                                                            <div class="d-flex flex-stack flex-grow-1">
                                                                <div class="fw-semibold">
                                                                    <h4 class="text-gray-900 fw-bold">Note</h4>
                                                                    <div class="fs-6 text-gray-700">This will add credits to the user's account. The change will take effect immediately.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Add Credits</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Modal - Add SMS Credits-->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end py-3">
                            {{ $users->links() }}
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
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endpush