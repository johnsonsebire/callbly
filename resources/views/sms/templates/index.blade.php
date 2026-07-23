@extends('layouts.master')

@section('title', 'SMS Message Templates')

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
                                        <i class="ki-outline ki-document fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">SMS Message Templates</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Reusable Copy</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Create, organize, and quickly reuse standardized SMS copy for marketing and notifications.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('sms.templates.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Create Template
                                </a>
                                <a href="{{ route('sms.compose') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-paper-plane me-1 fs-5"></i> Compose SMS
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Templates Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Saved Message Templates</span>
                            <span class="text-gray-400 mt-1 fs-7">Pre-formatted message templates available across your campaigns</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if($templates->isEmpty())
                            <div class="text-center py-12">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                    <i class="ki-outline ki-document fs-3hx text-primary"></i>
                                </div>
                                <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Templates Saved</h3>
                                <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Save time when dispatching broadcasts by creating reusable message copy templates with custom variables.</p>
                                <a href="{{ route('sms.templates.create') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                    <i class="ki-outline ki-plus fs-4 me-2"></i>Create Your First Template
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-180px">Template Name</th>
                                            <th class="min-w-280px">Message Copy Preview</th>
                                            <th class="min-w-150px">Description</th>
                                            <th class="min-w-120px">Created</th>
                                            <th class="min-w-180px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($templates as $template)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-40px me-3">
                                                            <span class="symbol-label bg-light-primary rounded-circle">
                                                                <i class="ki-outline ki-document fs-3 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-900 font-bold fs-6 mb-0">{{ $template->name }}</span>
                                                            <span class="text-gray-400 font-medium fs-8">{{ strlen($template->content) }} characters</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="bg-light-soft border border-gray-200 rounded-3 p-3 text-gray-700 fs-8 font-mono max-w-350px text-truncate">
                                                        {{ $template->content }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-gray-600 font-medium fs-7">
                                                        {{ $template->description ?: 'No description' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-800 font-semibold fs-7">{{ $template->created_at->format('M d, Y') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                        <a href="{{ route('sms.compose') }}?template={{ $template->id }}" 
                                                           class="btn btn-sm btn-primary font-bold shadow-xs px-3">
                                                            Use Template
                                                        </a>
                                                        <a href="{{ route('sms.templates.edit', $template->id) }}" 
                                                           class="btn btn-sm btn-light-primary font-bold px-3">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('sms.templates.delete', $template->id) }}" 
                                                              method="POST" 
                                                              class="m-0"
                                                              onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light-danger font-bold px-3">
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

                            <div class="d-flex justify-content-center mt-6">
                                {{ $templates->links() }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection