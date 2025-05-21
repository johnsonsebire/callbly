@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Templates List Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">SMS Templates</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Create and manage your message templates</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('sms.templates.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-plus-square fs-2 me-2"></i> Create Template
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($templates->isEmpty())
                            <div class="text-center my-5">
                                <i class="ki-outline ki-document fs-2tx text-gray-300 mb-5"></i>
                                <h4>No templates yet</h4>
                                <p class="text-muted">Create templates to save time when sending frequent messages.</p>
                                <a href="{{ route('sms.templates.create') }}" class="btn btn-primary mt-3">Create Your First Template</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-250px">Content</th>
                                            <th class="min-w-150px">Description</th>
                                            <th class="min-w-120px">Created</th>
                                            <th class="min-w-150px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($templates as $template)
                                            <tr>
                                                <td>{{ $template->name }}</td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 300px;">
                                                        {{ $template->content }}
                                                    </div>
                                                </td>
                                                <td>{{ $template->description }}</td>
                                                <td>{{ $template->created_at->format('M d, Y') }}</td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('sms.compose') }}?template={{ $template->id }}" 
                                                           class="btn btn-sm btn-light-primary me-2">
                                                            Use Template
                                                        </a>
                                                        <a href="{{ route('sms.templates.edit', $template->id) }}" 
                                                           class="btn btn-sm btn-light-secondary me-2">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('sms.templates.delete', $template->id) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $templates->links() }}
                            </div>
                        @endif
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