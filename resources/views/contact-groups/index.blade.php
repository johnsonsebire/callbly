@extends('layouts.master')

@section('title', 'Contact Group Segments')

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
                                        <i class="ki-outline ki-category fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Contact Group Segments</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Audience Segmentation</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Organize your audience directory into targeted groups for localized campaign broadcasts.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('contact-groups.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Create Group
                                </a>
                                <a href="{{ route('contacts.index') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-profile-user me-1 fs-5"></i> Contacts Directory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Audience Segments</span>
                            <span class="text-gray-400 mt-1 fs-7">Manage your saved contact lists and audience groups</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                            </div>
                        @endif
                        
                        <!-- Search Section -->
                        <div class="mb-6">
                            <form action="{{ route('contact-groups.index') }}" method="GET">
                                <div class="d-flex align-items-center position-relative max-w-500px">
                                    <i class="ki-outline ki-magnifier fs-2 position-absolute ms-4 text-gray-400"></i>
                                    <input type="text" name="search" class="form-control form-control-solid ps-12 pe-12 rounded-3" placeholder="Search groups by name..." value="{{ request('search') }}">
                                    <div class="d-flex align-items-center ms-3">
                                        <button class="btn btn-primary font-bold shadow-xs px-4" type="submit">Search</button>
                                        @if(request('search'))
                                            <a href="{{ route('contact-groups.index') }}" class="btn btn-light font-bold ms-2">Clear</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                        <th class="min-w-180px">Group Name</th>
                                        <th class="min-w-250px">Description</th>
                                        <th class="min-w-120px">Total Contacts</th>
                                        <th class="min-w-130px">Created Date</th>
                                        <th class="min-w-130px text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groups as $group)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40px me-3">
                                                        <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                            <i class="ki-outline ki-category fs-3"></i>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('contact-groups.show', $group) }}" class="text-gray-900 font-bold text-hover-primary fs-6 mb-0">{{ $group->name }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-gray-600 font-medium fs-7">
                                                    {{ \Illuminate\Support\Str::limit($group->description, 60) ?: 'No description' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-info font-bold fs-8 px-2.5 py-1">
                                                    {{ number_format($group->contacts_count) }} contacts
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-gray-800 font-semibold fs-7">{{ $group->created_at->format('M d, Y') }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end align-items-center gap-1">
                                                    <a href="{{ route('contact-groups.show', $group) }}" 
                                                       class="btn btn-sm btn-icon btn-light-info rounded-circle" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       title="View Group Details">
                                                        <i class="ki-outline ki-eye fs-4"></i>
                                                    </a>
                                                    <a href="{{ route('contact-groups.edit', $group) }}" 
                                                       class="btn btn-sm btn-icon btn-light-primary rounded-circle" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top" 
                                                       title="Edit Group">
                                                        <i class="ki-outline ki-pencil fs-4"></i>
                                                    </a>
                                                    <form action="{{ route('contact-groups.destroy', $group) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete this group?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-icon btn-light-danger rounded-circle" 
                                                                data-bs-toggle="tooltip" 
                                                                data-bs-placement="top" 
                                                                title="Delete Group">
                                                            <i class="ki-outline ki-trash fs-4"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-12">
                                                <div class="d-flex flex-column align-items-center">
                                                    <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                                        <i class="ki-outline ki-category fs-3hx text-primary"></i>
                                                    </div>
                                                    <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Contact Groups Found</h3>
                                                    <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Create audience groups to segment your contacts for high-converting targeted SMS broadcasts.</p>
                                                    <a href="{{ route('contact-groups.create') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                                        <i class="ki-outline ki-plus fs-4 me-2"></i>Create Your First Group
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-6">
                            {{ $groups->links() }}
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
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush