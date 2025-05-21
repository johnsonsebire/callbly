@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Contact Groups Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Contact Groups</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage your contact lists and segments</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('contact-groups.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-plus-square fs-2 me-2"></i> Create Group
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        
                        <div class="mb-5">
                            <form action="{{ route('contact-groups.index') }}" method="GET">
                                <div class="d-flex align-items-center position-relative">
                                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                                    <input type="text" name="search" class="form-control form-control-solid ps-12" placeholder="Search groups..." value="{{ request('search') }}">
                                    <div class="d-flex align-items-center ms-3">
                                        <button class="btn btn-light-primary me-2" type="submit">Search</button>
                                        @if(request('search'))
                                            <a href="{{ route('contact-groups.index') }}" class="btn btn-light">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Name</th>
                                        <th class="min-w-250px">Description</th>
                                        <th class="min-w-100px">Contacts</th>
                                        <th class="min-w-120px">Created</th>
                                        <th class="min-w-150px text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groups as $group)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-45px me-3">
                                                        <span class="symbol-label bg-light-primary text-primary">
                                                            <i class="ki-outline ki-people fs-2"></i>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('contact-groups.show', $group) }}" class="text-dark text-hover-primary fw-bold">{{ $group->name }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ \Illuminate\Support\Str::limit($group->description, 50) ?: 'No description' }}</td>
                                            <td>
                                                <span class="badge badge-light-primary fs-7 fw-semibold">{{ $group->contacts_count }} contacts</span>
                                            </td>
                                            <td>{{ $group->created_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('contact-groups.show', $group) }}" class="btn btn-sm btn-light-info me-1">
                                                    <i class="ki-outline ki-eye fs-2"></i>
                                                </a>
                                                <a href="{{ route('contact-groups.edit', $group) }}" class="btn btn-sm btn-light-primary me-1">
                                                    <i class="ki-outline ki-pencil fs-2"></i>
                                                </a>
                                                <form action="{{ route('contact-groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this group?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger">
                                                        <i class="ki-outline ki-trash fs-2"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-10">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="ki-outline ki-people fs-2tx text-gray-300 mb-5"></i>
                                                    <span class="text-gray-600 fs-5 fw-semibold">No contact groups found</span>
                                                    <span class="text-gray-400 fs-7">Create groups to organize your contacts</span>
                                                    <a href="{{ route('contact-groups.create') }}" class="btn btn-primary mt-3">Create Your First Group</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $groups->links() }}
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