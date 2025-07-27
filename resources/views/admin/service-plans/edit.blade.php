@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card">
                    <div class="card-header border-0 pt-5">
                        <div class="card-title">
                            <h2 class="fw-bold">Edit Service Plan</h2>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('admin.service-plans.index') }}" class="btn btn-sm btn-light">
                                <i class="ki-duotone ki-arrow-left fs-2"></i>Back to Service Plans
                            </a>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                                <div class="d-flex flex-column">
                                    <h4 class="mb-1 text-danger">Validation Error</h4>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                                    <i class="ki-outline ki-cross fs-2 text-danger"></i>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.service-plans.update', $servicePlan) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mb-6">
                                <div class="col-lg-6">
                                    <label for="name" class="col-form-label required fw-semibold fs-6">Plan Name</label>
                                    <input type="text" name="name" id="name" class="form-control form-control-solid" 
                                           value="{{ old('name', $servicePlan->name) }}" placeholder="Enter plan name" required>
                                </div>
                                <div class="col-lg-6">
                                    <label for="type" class="col-form-label required fw-semibold fs-6">Service Type</label>
                                    <select name="type" id="type" class="form-select form-select-solid" required>
                                        <option value="">Select service type</option>
                                        <option value="sms" {{ old('type', $servicePlan->type) === 'sms' ? 'selected' : '' }}>SMS</option>
                                        <option value="voice" {{ old('type', $servicePlan->type) === 'voice' ? 'selected' : '' }}>Voice</option>
                                        <option value="contact-center" {{ old('type', $servicePlan->type) === 'contact-center' ? 'selected' : '' }}>Contact Center</option>
                                        <option value="virtual-number" {{ old('type', $servicePlan->type) === 'virtual-number' ? 'selected' : '' }}>Virtual Numbers</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-12">
                                    <label for="description" class="col-form-label required fw-semibold fs-6">Description</label>
                                    <textarea name="description" id="description" class="form-control form-control-solid" 
                                              rows="3" placeholder="Enter plan description" required>{{ old('description', $servicePlan->description) }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-4">
                                    <label for="price" class="col-form-label required fw-semibold fs-6">Price ({{ auth()->user()->currency->symbol }})</label>
                                    <input type="number" name="price" id="price" class="form-control form-control-solid" 
                                           value="{{ old('price', number_format($servicePlan->user_currency_price, 2, '.', '')) }}" placeholder="0.00" step="0.01" min="0" required>
                                    <div class="form-text">Current: {{ auth()->user()->formatAmount($servicePlan->price) }} (converted from base currency)</div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="validity_days" class="col-form-label required fw-semibold fs-6">Validity (Days)</label>
                                    <input type="number" name="validity_days" id="validity_days" class="form-control form-control-solid" 
                                           value="{{ old('validity_days', $servicePlan->validity_days) }}" placeholder="30" min="1" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="units" class="col-form-label required fw-semibold fs-6">Units</label>
                                    <input type="number" name="units" id="units" class="form-control form-control-solid" 
                                           value="{{ old('units', $servicePlan->units) }}" placeholder="1000" min="0" required>
                                    <div class="form-text">Credits/Minutes/Numbers depending on service type</div>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-12">
                                    <label class="col-form-label required fw-semibold fs-6">Features</label>
                                    <div id="features-container">
                                        @php
                                            $features = old('features', $servicePlan->features ?: []);
                                        @endphp
                                        @if($features)
                                            @foreach($features as $index => $feature)
                                                <div class="input-group mb-3 feature-row">
                                                    <input type="text" name="features[]" class="form-control form-control-solid" 
                                                           value="{{ $feature }}" placeholder="Enter feature description">
                                                    <button type="button" class="btn btn-outline-danger remove-feature">
                                                        <i class="ki-duotone ki-trash fs-2"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-3 feature-row">
                                                <input type="text" name="features[]" class="form-control form-control-solid" 
                                                       placeholder="Enter feature description">
                                                <button type="button" class="btn btn-outline-danger remove-feature">
                                                    <i class="ki-duotone ki-trash fs-2"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" id="add-feature" class="btn btn-light-primary btn-sm">
                                        <i class="ki-duotone ki-plus fs-2"></i>Add Feature
                                    </button>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col-lg-6">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_popular" id="is_popular" 
                                               value="1" {{ old('is_popular', $servicePlan->is_popular) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold fs-6" for="is_popular">
                                            Mark as Popular Plan
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                               value="1" {{ old('is_active', $servicePlan->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold fs-6" for="is_active">
                                            Activate Plan
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.service-plans.index') }}" class="btn btn-light me-5">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Service Plan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add new feature
    $('#add-feature').click(function() {
        const newFeatureRow = `
            <div class="input-group mb-3 feature-row">
                <input type="text" name="features[]" class="form-control form-control-solid" 
                       placeholder="Enter feature description">
                <button type="button" class="btn btn-outline-danger remove-feature">
                    <i class="ki-duotone ki-trash fs-2"></i>
                </button>
            </div>
        `;
        $('#features-container').append(newFeatureRow);
    });

    // Remove feature
    $(document).on('click', '.remove-feature', function() {
        if ($('.feature-row').length > 1) {
            $(this).closest('.feature-row').remove();
        } else {
            alert('At least one feature is required');
        }
    });
});
</script>
@endpush
