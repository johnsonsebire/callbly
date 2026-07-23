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

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                        <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                        <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Main Templates Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <div class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Saved Message Templates</span>
                            <span class="text-gray-400 mt-1 fs-7">Pre-formatted message copy templates for instant dispatch</span>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Search Bar -->
                                <div class="position-relative">
                                    <i class="ki-outline ki-magnifier fs-4 text-gray-500 position-absolute top-50 translate-middle-y ms-3"></i>
                                    <input type="text" id="templateSearchInput" class="form-control form-control-solid ps-10 rounded-3 fs-7" placeholder="Search templates...">
                                </div>
                                <!-- View Switcher -->
                                <div class="btn-group rounded-3 p-1 bg-light-soft border border-gray-200" role="group">
                                    <button type="button" class="btn btn-sm btn-icon btn-color-gray-600 btn-active-primary active" id="btnGridView" title="Grid Card View">
                                        <i class="ki-outline ki-element-11 fs-3"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-color-gray-600 btn-active-primary" id="btnTableView" title="Table View">
                                        <i class="ki-outline ki-row-horizontal fs-3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-4">
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
                            <!-- Grid View Container (Default) -->
                            <div id="templatesGridView" class="row g-4">
                                @foreach($templates as $template)
                                    <div class="col-md-6 col-xl-4 template-card-item" data-name="{{ strtolower($template->name) }}" data-content="{{ strtolower($template->content) }}">
                                        <div class="card border border-gray-200 shadow-xs h-100 p-5 d-flex flex-column justify-content-between" style="border-radius: 1rem;">
                                            <div>
                                                <!-- Card Header -->
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="symbol symbol-40px me-1">
                                                            <span class="symbol-label bg-light-primary rounded-circle">
                                                                <i class="ki-outline ki-document fs-3 text-primary"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-bold text-gray-900 fs-6 mb-0">{{ $template->name }}</h4>
                                                            <span class="text-gray-400 font-medium fs-8">{{ $template->created_at->format('M d, Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="badge badge-light-primary font-bold fs-8 px-2 py-1">
                                                        {{ strlen($template->content) }} chars
                                                    </span>
                                                </div>

                                                <!-- Message Bubble Content Box -->
                                                <div class="p-4 rounded-3 border border-gray-200 bg-light-soft mb-3 relative overflow-hidden" style="background-color: #f8fafc; font-family: 'JetBrains Mono', Consolas, monospace;">
                                                    <div class="fs-7 text-gray-800 lh-base" style="white-space: pre-wrap; word-break: break-word;">{{ $template->content }}</div>
                                                </div>

                                                @if($template->description)
                                                    <p class="text-gray-500 fs-8 mb-4">
                                                        <i class="ki-outline ki-information-5 me-1 text-gray-400"></i>{{ $template->description }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Card Actions Footer -->
                                            <div class="pt-3 border-top border-gray-100 d-flex align-items-center justify-content-between gap-2 mt-auto">
                                                <a href="{{ route('sms.compose') }}?template={{ $template->id }}" class="btn btn-primary btn-sm font-bold shadow-xs px-3 flex-grow-1 text-center">
                                                    <i class="ki-outline ki-paper-plane me-1 fs-5"></i> Use Template
                                                </a>
                                                <a href="{{ route('sms.templates.edit', $template->id) }}" class="btn btn-light-primary btn-sm font-bold px-3">
                                                    <i class="ki-outline ki-pencil me-1 fs-5"></i> Edit
                                                </a>
                                                <form action="{{ route('sms.templates.delete', $template->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this template?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-light-danger btn-sm rounded-circle" title="Delete Template">
                                                        <i class="ki-outline ki-trash fs-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Table View Container (Hidden by Default) -->
                            <div id="templatesTableView" class="table-responsive d-none">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-180px">Template Name</th>
                                            <th class="min-w-300px">Message Copy Preview</th>
                                            <th class="min-w-150px">Description</th>
                                            <th class="min-w-120px">Created</th>
                                            <th class="min-w-180px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($templates as $template)
                                            <tr class="template-table-row" data-name="{{ strtolower($template->name) }}" data-content="{{ strtolower($template->content) }}">
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
                                                    <div class="bg-light-soft border border-gray-200 rounded-3 p-3 text-gray-800 fs-7 font-mono max-w-400px">
                                                        {{ Str::limit($template->content, 120) }}
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
                                                        <a href="{{ route('sms.compose') }}?template={{ $template->id }}" class="btn btn-sm btn-primary font-bold shadow-xs px-3">
                                                            Use Template
                                                        </a>
                                                        <a href="{{ route('sms.templates.edit', $template->id) }}" class="btn btn-sm btn-light-primary font-bold px-3">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('sms.templates.delete', $template->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this template?');">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnGridView = document.getElementById('btnGridView');
        const btnTableView = document.getElementById('btnTableView');
        const templatesGridView = document.getElementById('templatesGridView');
        const templatesTableView = document.getElementById('templatesTableView');
        const templateSearchInput = document.getElementById('templateSearchInput');

        if (btnGridView && btnTableView) {
            btnGridView.addEventListener('click', function() {
                btnGridView.classList.add('active');
                btnTableView.classList.remove('active');
                templatesGridView.classList.remove('d-none');
                templatesTableView.classList.add('d-none');
            });

            btnTableView.addEventListener('click', function() {
                btnTableView.classList.add('active');
                btnGridView.classList.remove('active');
                templatesTableView.classList.remove('d-none');
                templatesGridView.classList.add('d-none');
            });
        }

        if (templateSearchInput) {
            templateSearchInput.addEventListener('keyup', function() {
                const term = this.value.toLowerCase().trim();
                
                document.querySelectorAll('.template-card-item').forEach(function(item) {
                    const name = item.getAttribute('data-name') || '';
                    const content = item.getAttribute('data-content') || '';
                    if (name.includes(term) || content.includes(term)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });

                document.querySelectorAll('.template-table-row').forEach(function(row) {
                    const name = row.getAttribute('data-name') || '';
                    const content = row.getAttribute('data-content') || '';
                    if (name.includes(term) || content.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endpush