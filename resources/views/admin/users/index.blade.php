@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Users Management</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Manage system users</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-duotone ki-plus fs-2"></i>New User
                            </a>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Name</th>
                                        <th class="min-w-150px">Email</th>
                                        <th class="min-w-100px">Role</th>
                                        <th class="min-w-100px">Status</th>
                                        <th class="min-w-100px text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-5">
                                                    @if($user->profile_photo_url)
                                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" />
                                                    @else
                                                        <div class="symbol-label fs-3 bg-light-primary text-primary">
                                                            {{ substr($user->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-dark fw-bold text-hover-primary fs-6">{{ $user->name }}</a>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">Joined {{ $user->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-light-primary fs-7 m-1">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge badge-light-success">Verified</span>
                                            @else
                                                <span class="badge badge-light-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <i class="ki-duotone ki-pencil fs-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="ki-duotone ki-trash fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex flex-wrap py-2 mr-3">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection