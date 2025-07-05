@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <!-- Custom Fields Card -->
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Custom Fields</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">Manage custom contact fields</span>
                        </h3>
                        <div class="card-toolbar">
                            <a href="{{ route('custom-fields.create') }}" class="btn btn-sm btn-primary">
                                <i class="ki-outline ki-plus-square fs-2 me-2"></i> Add Custom Field
                            </a>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($customFields->count() > 0)
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="custom-fields-table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Field Name</th>
                                            <th class="min-w-100px">Type</th>
                                            <th class="min-w-100px">Required</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-80px">Sort Order</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody id="sortable-fields">
                                        @foreach($customFields as $field)
                                            <tr data-id="{{ $field->id }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex justify-content-start flex-column">
                                                            <span class="text-dark fw-bold text-hover-primary fs-6">{{ $field->name }}</span>
                                                            @if($field->description)
                                                                <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $field->description }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light-primary">{{ ucfirst($field->field_type) }}</span>
                                                </td>
                                                <td>
                                                    @if($field->is_required)
                                                        <span class="badge badge-light-danger">Required</span>
                                                    @else
                                                        <span class="badge badge-light-secondary">Optional</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('custom-fields.toggle-status', $field) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm {{ $field->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                            {{ $field->is_active ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block fs-6">{{ $field->sort_order }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end flex-shrink-0">
                                                        <a href="{{ route('custom-fields.edit', $field) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit">
                                                            <i class="ki-outline ki-pencil fs-2"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('custom-fields.destroy', $field) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this custom field?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm" title="Delete">
                                                                <i class="ki-outline ki-trash fs-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        @else
                            <div class="text-center py-10">
                                <div class="mb-7">
                                    <i class="ki-outline ki-setting-2 fs-5x text-gray-400"></i>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500 mb-5">No custom fields found</div>
                                <a href="{{ route('custom-fields.create') }}" class="btn btn-primary">
                                    Create your first custom field
                                </a>
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

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    // Make table sortable
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
                    // Update sort order numbers in the UI
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
