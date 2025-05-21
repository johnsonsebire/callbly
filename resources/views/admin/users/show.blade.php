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
                    <div class="col-xl-8">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">User Information</h2>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary me-2">
                                            <i class="ki-outline ki-pencil fs-2"></i>Edit User
                                        </a>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">
                                            <i class="ki-outline ki-arrow-left fs-2"></i>Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
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
                                
                                <div class="separator my-10"></div>

                                <!--begin::Details-->
                                <div class="d-flex flex-wrap py-5">
                                    <!--begin::Row-->
                                    <div class="flex-equal me-5">
                                        <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2 m-0">
                                            <tr>
                                                <td class="text-gray-400 min-w-175px w-175px">Full Name:</td>
                                                <td class="text-gray-800">{{ $user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Email:</td>
                                                <td class="text-gray-800">{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Phone:</td>
                                                <td class="text-gray-800">{{ $user->phone ?? 'Not set' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Company Name:</td>
                                                <td class="text-gray-800">{{ $user->company_name ?? 'Not set' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Roles:</td>
                                                <td>
                                                    @foreach($user->roles as $role)
                                                        <span class="badge badge-light-primary fs-7 m-1">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Account Created:</td>
                                                <td class="text-gray-800">{{ $user->created_at->format('M d, Y H:i:s') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-gray-400">Email Verified:</td>
                                                <td>
                                                    @if($user->email_verified_at)
                                                        <span class="badge badge-light-success">Verified on {{ $user->email_verified_at->format('M d, Y H:i:s') }}</span>
                                                    @else
                                                        <span class="badge badge-light-warning">Not Verified</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100 mt-5">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Recent Activity</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th class="min-w-150px">Type</th>
                                                <th class="min-w-150px">Details</th>
                                                <th class="min-w-150px">Date</th>
                                                <th class="min-w-100px text-end">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($activities) && $activities->count() > 0)
                                                @foreach($activities as $activity)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-45px me-5">
                                                                <div class="symbol-label bg-light-primary">
                                                                    <i class="ki-outline ki-abstract-26 fs-3 text-primary"></i>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{ $activity->type }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark fw-semibold d-block fs-7">{{ $activity->details }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted fw-semibold d-block fs-7">{{ $activity->created_at->format('M d, Y H:i:s') }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="badge badge-light-{{ $activity->status === 'success' ? 'success' : 'danger' }} fs-7 fw-bold">{{ $activity->status }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4" class="text-center py-10">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="ki-outline ki-calendar fs-3x text-gray-300 mb-5"></i>
                                                            <span class="text-gray-600 fs-5 fw-semibold">No activity found</span>
                                                            <span class="text-gray-400 fs-7 mb-5">This user has no recorded activity yet</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if(isset($activities) && $activities->count() > 0)
                                <div class="d-flex justify-content-end mt-6">
                                    {{ $activities->links() }}
                                </div>
                                @endif
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    
                    <div class="col-xl-4">
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bold">Account Status</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <div class="d-flex flex-column">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-5">
                                                <span class="symbol-label bg-light-success">
                                                    <i class="ki-outline ki-shield-tick fs-3 text-success"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Email Verified</a>
                                                <span class="text-muted fw-bold">{{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                    <div class="separator separator-dashed my-5"></div>
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="symbol symbol-30px me-5">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-calendar fs-3 text-primary"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">Member Since</a>
                                                <span class="text-muted fw-bold">{{ $user->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Item-->
                                </div>

                                <!-- SMS Credit Balance -->
                                <div class="separator separator-dashed my-8"></div>
                                <div class="d-flex flex-stack flex-wrap mb-5">
                                    <div class="d-flex flex-column justify-content-center flex-row-fluid">
                                        <div class="d-flex fw-semibold align-items-center">
                                            <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                                            <div class="text-gray-500 flex-grow-1 me-4">SMS Credits</div>
                                            <div class="fw-bold text-gray-700 text-xxl-end">{{ number_format($user->sms_credits ?? 0) }}</div>
                                        </div>
                                        <div class="d-flex fw-semibold align-items-center my-3">
                                            <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                                            <div class="text-gray-500 flex-grow-1 me-4">USSD Credits</div>
                                            <div class="fw-bold text-gray-700 text-xxl-end">{{ number_format($user->ussd_credits ?? 0) }}</div>
                                        </div>
                                        <div class="d-flex fw-semibold align-items-center">
                                            <div class="bullet w-8px h-3px rounded-2 bg-warning me-3"></div>
                                            <div class="text-gray-500 flex-grow-1 me-4">Wallet Balance</div>
                                            <div class="fw-bold text-gray-700 text-xxl-end">{{ $user->wallet_balance ?? '₵0.00' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        
                        <!--begin::Card-->
                        <div class="card card-flush h-xl-100 mt-5">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2 class="fw-bold">Actions</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-5">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-flex btn-primary w-100 mb-5">
                                    <i class="ki-outline ki-pencil fs-3 me-2"></i>
                                    <span class="fw-semibold">Edit User</span>
                                </a>
                                
                                <button type="button" class="btn btn-flex btn-light-warning w-100 mb-5" data-bs-toggle="modal" data-bs-target="#reset_password_modal">
                                    <i class="ki-outline ki-lock-2 fs-3 me-2"></i>
                                    <span class="fw-semibold">Reset Password</span>
                                </button>
                                
                                @if(!$user->email_verified_at)
                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="d-inline w-100 mb-5">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="1">
                                    <button type="submit" class="btn btn-flex btn-light-success w-100">
                                        <i class="ki-outline ki-shield-tick fs-3 me-2"></i>
                                        <span class="fw-semibold">Verify Email</span>
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-flex btn-light-danger w-100" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="ki-outline ki-trash fs-3 me-2"></i>
                                        <span class="fw-semibold">Delete User</span>
                                    </button>
                                </form>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>


<!-- Reset Password Modal -->
<div class="modal fade" tabindex="-1" id="reset_password_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h3 class="modal-title">Reset User Password</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <p>You are about to reset the password for <strong>{{ $user->name }}</strong>. Enter a new password below:</p>
                    
                    <div class="mb-5">
                        <label class="form-label required">New Password</label>
                        <input type="password" class="form-control" name="password" required />
                    </div>
                    
                    <div class="mb-5">
                        <label class="form-label required">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection