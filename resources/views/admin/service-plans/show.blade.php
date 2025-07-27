@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <div class="card-title">
                            <h2 class="fw-bold">{{ $servicePlan->name }}</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.service-plans.edit', $servicePlan) }}" class="btn btn-sm btn-primary">
                                    <i class="ki-duotone ki-pencil fs-2"></i>Edit Plan
                                </a>
                                <a href="{{ route('admin.service-plans.index') }}" class="btn btn-sm btn-light">
                                    <i class="ki-duotone ki-arrow-left fs-2"></i>Back to Plans
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body py-3">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card card-bordered">
                                    <div class="card-body">
                                        <div class="row mb-7">
                                            <div class="col-lg-6">
                                                <label class="fw-semibold fs-6 text-gray-800">Plan Name</label>
                                                <div class="fw-bold fs-6 text-gray-600">{{ $servicePlan->name }}</div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fw-semibold fs-6 text-gray-800">Service Type</label>
                                                <div class="fw-bold fs-6">
                                                    <span class="badge badge-light-{{ $servicePlan->type === 'sms' ? 'info' : ($servicePlan->type === 'voice' ? 'success' : ($servicePlan->type === 'contact-center' ? 'warning' : 'primary')) }}">
                                                        {{ ucwords(str_replace('-', ' ', $servicePlan->type)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-7">
                                            <div class="col-lg-12">
                                                <label class="fw-semibold fs-6 text-gray-800">Description</label>
                                                <div class="fw-bold fs-6 text-gray-600">{{ $servicePlan->description }}</div>
                                            </div>
                                        </div>

                                        <div class="row mb-7">
                                            <div class="col-lg-4">
                                                <label class="fw-semibold fs-6 text-gray-800">Price</label>
                                                <div class="fw-bold fs-6 text-gray-600">{{ auth()->user()->formatAmount($servicePlan->price) }}</div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="fw-semibold fs-6 text-gray-800">Validity</label>
                                                <div class="fw-bold fs-6 text-gray-600">{{ $servicePlan->validity_days }} days</div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="fw-semibold fs-6 text-gray-800">Units</label>
                                                <div class="fw-bold fs-6 text-gray-600">{{ number_format($servicePlan->units) }}</div>
                                            </div>
                                        </div>

                                        <div class="row mb-7">
                                            <div class="col-lg-12">
                                                <label class="fw-semibold fs-6 text-gray-800">Features</label>
                                                <div class="mt-3">
                                                    @if($servicePlan->features && count($servicePlan->features) > 0)
                                                        <ul class="list-unstyled">
                                                            @foreach($servicePlan->features as $feature)
                                                                <li class="d-flex align-items-center py-2">
                                                                    <i class="ki-duotone ki-check-circle fs-2 text-success me-3">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                    <span class="fw-semibold fs-6 text-gray-700">{{ $feature }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span class="text-muted">No features listed</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fw-semibold fs-6 text-gray-800">Popular Plan</label>
                                                <div class="fw-bold fs-6">
                                                    @if($servicePlan->is_popular)
                                                        <span class="badge badge-light-warning">Yes</span>
                                                    @else
                                                        <span class="badge badge-light-secondary">No</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fw-semibold fs-6 text-gray-800">Status</label>
                                                <div class="fw-bold fs-6">
                                                    @if($servicePlan->is_active)
                                                        <span class="badge badge-light-success">Active</span>
                                                    @else
                                                        <span class="badge badge-light-danger">Inactive</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-bordered">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h3 class="fw-bold">Plan Statistics</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-7">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <span class="symbol-label bg-light-primary">
                                                        <i class="ki-duotone ki-chart-simple text-primary fs-2x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fs-4 fw-bold text-gray-900">{{ $servicePlan->orders->where('status', 'completed')->count() }}</div>
                                                    <div class="fs-7 text-muted">Total Orders</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-7">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <span class="symbol-label bg-light-success">
                                                        <i class="ki-duotone ki-check-circle text-success fs-2x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fs-4 fw-bold text-gray-900">
                                                        {{ $servicePlan->orders->where('status', 'completed')->where('expires_at', '>', now())->count() }}
                                                    </div>
                                                    <div class="fs-7 text-muted">Active Subscriptions</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-7">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-50px me-5">
                                                    <span class="symbol-label bg-light-info">
                                                        <i class="ki-duotone ki-dollar text-info fs-2x">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fs-4 fw-bold text-gray-900">
                                                        ₦{{ number_format($servicePlan->orders->where('status', 'completed')->sum('amount'), 2) }}
                                                    </div>
                                                    <div class="fs-7 text-muted">Total Revenue</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed my-7"></div>

                                        <div class="mb-5">
                                            <label class="fw-semibold fs-6 text-gray-800">Created</label>
                                            <div class="fw-bold fs-6 text-gray-600">{{ $servicePlan->created_at->format('M d, Y g:i A') }}</div>
                                        </div>

                                        <div class="mb-5">
                                            <label class="fw-semibold fs-6 text-gray-800">Last Updated</label>
                                            <div class="fw-bold fs-6 text-gray-600">{{ $servicePlan->updated_at->format('M d, Y g:i A') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
