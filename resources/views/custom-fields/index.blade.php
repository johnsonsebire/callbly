@extends('layouts.master')

@section('title', 'Custom Audience Attributes')

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
                                        <i class="ki-outline ki-setting-2 fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Custom Audience Attributes</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Schema Manager</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Define custom parameters, drag-and-drop sort order, and manage audience schema fields.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('custom-fields.create') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Add Custom Field
                                </a>
                                <a href="{{ route('contacts.index') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-profile-user me-1 fs-5"></i> Audience Directory
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Schema Attributes</span>
                            <span class="text-gray-400 mt-1 fs-7">Drag table rows to reorder how fields appear during contact entry</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center p-4 mb-5 rounded-3 shadow-xs">
                                <i class="ki-outline ki-cross-circle fs-2x text-danger me-3"></i>
                                <span class="font-bold text-gray-800 fs-6">{{ session('error') }}</span>
                            </div>
                        @endif

                        @if($customFields->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4" id="custom-fields-table">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-180px">Field Name & Label</th>
                                            <th class="min-w-120px">Data Type</th>
                                            <th class="min-w-100px">Requirement</th>
                                            <th class="min-w-120px">Status Toggle</th>
                                            <th class="min-w-80px">Sort Order</th>
                                            <th class="min-w-120px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="sortable-fields">
                                        @foreach($customFields as $field)
                                            <tr data-id="{{ $field->id }}" class="hover-bg-light cursor-move">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="ki-outline ki-abstract-26 me-3 fs-3 text-gray-400"></i>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-gray-900 font-bold fs-6 mb-0">{{ $field->name }}</span>
                                                            @if($field->description)
                                                                <span class="text-gray-400 font-medium fs-8">{{ $field->description }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-primary font-bold fs-8 px-2.5 py-1">
                                                        {{ ucfirst($field->field_type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($field->is_required)
                                                        <span class="badge badge-light-danger font-bold fs-8 px-2 py-0.5">Required</span>
                                                    @else
                                                        <span class="badge badge-light-secondary font-medium fs-8 px-2 py-0.5">Optional</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('custom-fields.toggle-status', $field) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm {{ $field->is_active ? 'btn-light-success text-success' : 'btn-light-secondary text-gray-500' }} font-bold px-3 py-1">
                                                            <i class="ki-outline {{ $field->is_active ? 'ki-check-circle' : 'ki-cross-circle' }} me-1 fs-6"></i>
                                                            {{ $field->is_active ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <span class="font-mono text-gray-800 font-bold fs-7 px-2.5 py-1 bg-light-soft rounded-2">{{ $field->sort_order }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                                        <a href="{{ route('custom-fields.edit', $field) }}" 
                                                           class="btn btn-sm btn-icon btn-light-primary rounded-circle" 
                                                           data-bs-toggle="tooltip" 
                                                           data-bs-placement="top" 
                                                           title="Edit Field">
                                                            <i class="ki-outline ki-pencil fs-4"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('custom-fields.destroy', $field) }}" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to delete this custom field?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-icon btn-light-danger rounded-circle" 
                                                                    data-bs-toggle="tooltip" 
                                                                    data-bs-placement="top" 
                                                                    title="Delete Field">
                                                                <i class="ki-outline ki-trash fs-4"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                    <i class="ki-outline ki-setting-2 fs-3hx text-primary"></i>
                                </div>
                                <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Custom Fields Configured</h3>
                                <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Create custom audience parameters (e.g. VIP level, Loyalty ID) to capture rich customer profile details.</p>
                                <a href="{{ route('custom-fields.create') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                    <i class="ki-outline ki-plus fs-4 me-2"></i>Create Your First Custom Field
                                </a>
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    $("#sortable-fields").sortable({
        items: "tr",
        cursor: "move",
        update: function(event, ui) {
            var order = $(this).sortable('toArray', {attribute: 'data-id'});
            
            $.post('{{ route("custom-fields.sort") }}', {
                _token: '{{ csrf_token() }}',
                order: order
            }).done(function(response) {
                if (response.success) {
                    $('#sortable-fields tr').each(function(index) {
                        $(this).find('td:nth-child(5) span').text(index + 1);
                    });
                }
            }).fail(function() {
                alert('Failed to update sort order');
            });
        }
    });
});
</script>
@endpush
