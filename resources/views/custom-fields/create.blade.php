@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Custom Field</h3>
                        <div class="card-toolbar">
                            <a href="{{ route('custom-fields.index') }}" class="btn btn-sm btn-secondary">
                                <i class="ki-outline ki-arrow-left fs-2 me-2"></i> Back to Custom Fields
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('custom-fields.store') }}">
                            @csrf
                            
                            <div class="row mb-6">
                                <div class="col-lg-6">
                                    <label class="col-form-label required fw-semibold fs-6">Field Name</label>
                                    <input type="text" name="name" class="form-control form-control-solid @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" placeholder="Enter field name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6">
                                    <label class="col-form-label required fw-semibold fs-6">Field Type</label>
                                    <select name="field_type" class="form-select form-select-solid @error('field_type') is-invalid @enderror" required>
                                        <option value="">Select field type</option>
                                        <option value="text" {{ old('field_type') == 'text' ? 'selected' : '' }}>Text</option>
                                        <option value="number" {{ old('field_type') == 'number' ? 'selected' : '' }}>Number</option>
                                        <option value="email" {{ old('field_type') == 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="phone" {{ old('field_type') == 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="date" {{ old('field_type') == 'date' ? 'selected' : '' }}>Date</option>
                                        <option value="select" {{ old('field_type') == 'select' ? 'selected' : '' }}>Select/Dropdown</option>
                                        <option value="textarea" {{ old('field_type') == 'textarea' ? 'selected' : '' }}>Textarea</option>
                                        <option value="checkbox" {{ old('field_type') == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                        <option value="url" {{ old('field_type') == 'url' ? 'selected' : '' }}>URL</option>
                                    </select>
                                    @error('field_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-12">
                                    <label class="col-form-label fw-semibold fs-6">Description</label>
                                    <textarea name="description" class="form-control form-control-solid @error('description') is-invalid @enderror" 
                                              rows="3" placeholder="Enter field description (optional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6" id="options-container" style="display: none;">
                                <div class="col-lg-12">
                                    <label class="col-form-label fw-semibold fs-6">Options</label>
                                    <div class="text-muted fs-7 mb-3">Enter each option on a new line</div>
                                    <textarea name="options" class="form-control form-control-solid @error('options') is-invalid @enderror" 
                                              rows="5" placeholder="Option 1&#10;Option 2&#10;Option 3">{{ old('options') }}</textarea>
                                    @error('options')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-6">
                                    <label class="col-form-label fw-semibold fs-6">Default Value</label>
                                    <input type="text" name="default_value" class="form-control form-control-solid @error('default_value') is-invalid @enderror" 
                                           value="{{ old('default_value') }}" placeholder="Enter default value (optional)">
                                    @error('default_value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-lg-6">
                                    <label class="col-form-label fw-semibold fs-6">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control form-control-solid @error('sort_order') is-invalid @enderror" 
                                           value="{{ old('sort_order', 1) }}" min="1" placeholder="Sort order">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-6">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_required" id="is_required" 
                                               value="1" {{ old('is_required') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-gray-700 fs-6" for="is_required">
                                            Required Field
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                               value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold text-gray-700 fs-6" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('custom-fields.index') }}" class="btn btn-secondary me-3">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-outline ki-check fs-2 me-2"></i> Create Custom Field
                                </button>
                            </div>
                        </form>
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
<script>
$(document).ready(function() {
    // Show/hide options field based on field type
    $('select[name="field_type"]').change(function() {
        if ($(this).val() === 'select') {
            $('#options-container').show();
        } else {
            $('#options-container').hide();
        }
    });
    
    // Trigger change event on page load
    $('select[name="field_type"]').trigger('change');
});
</script>
@endpush
