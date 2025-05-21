@extends('layouts.master')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <div class="card-title d-flex align-items-center">
                                    <h2 class="fw-bold mb-0">Browse Virtual Numbers</h2>
                                </div>
                                <div class="card-toolbar d-flex align-items-center">
                                    <a href="{{ route('virtual-numbers.index') }}" class="btn btn-light-primary">
                                        <i class="ki-outline ki-arrow-left me-1"></i>Back to Dashboard
                                    </a>
                                </div>
                            </div>
                            <!--end::Card header-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
                
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Available Number Plans</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="row g-4">
                                    @forelse($plans as $plan)
                                    <div class="col-md-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $plan->name }}</h5>
                                                <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                                                <div class="d-flex align-items-baseline mb-4">
                                                    <span class="fs-2x fw-bold text-primary">{{ $plan->currency->symbol }}{{ number_format($plan->price, 2) }}</span>
                                                    <span class="text-muted ms-2">/{{ $plan->billing_cycle }}</span>
                                                </div>
                                                <ul class="list-unstyled mb-4">
                                                    @foreach(json_decode($plan->features) as $feature)
                                                    <li class="d-flex align-items-center mb-2">
                                                        <i class="ki-outline ki-check-circle fs-6 text-success me-2"></i>
                                                        <span>{{ $feature }}</span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                <div class="text-center">
                                                    <button class="btn btn-primary select-plan" data-plan-id="{{ $plan->id }}">
                                                        Select Plan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            No plans are currently available. Please check back later.
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-12">
                        <!--begin::Card-->
                        <div class="card card-flush">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Available Numbers</h2>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex flex-row">
                                        <select class="form-select form-select-sm me-2" id="country-filter">
                                            <option value="">All Countries</option>
                                            <option value="US">United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="NG">Nigeria</option>
                                        </select>
                                        <select class="form-select form-select-sm" id="type-filter">
                                            <option value="">All Types</option>
                                            <option value="local">Local</option>
                                            <option value="tollfree">Toll-Free</option>
                                            <option value="mobile">Mobile</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th>Number</th>
                                                <th>Country</th>
                                                <th>Type</th>
                                                <th>Monthly Fee</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-6 fw-semibold text-gray-600">
                                            @forelse($availableNumbers as $number)
                                            <tr>
                                                <td>{{ $number->number }}</td>
                                                <td>{{ $number->country_code }}</td>
                                                <td>{{ ucfirst($number->number_type) }}</td>
                                                <td>{{ $number->currency->symbol }}{{ number_format($number->monthly_fee, 2) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary purchase-number" 
                                                        data-number="{{ $number->number }}"
                                                        data-country="{{ $number->country_code }}"
                                                        data-type="{{ $number->number_type }}"
                                                        data-fee="{{ $number->monthly_fee }}">
                                                        Purchase
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="text-gray-500">No available numbers found</div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap mt-5">
                                    <div class="text-muted">
                                        Showing {{ $availableNumbers->firstItem() ?? 0 }} to {{ $availableNumbers->lastItem() ?? 0 }} of {{ $availableNumbers->total() }} entries
                                    </div>
                                    {{ $availableNumbers->links() }}
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                </div>
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
</div>

<!-- Purchase Number Modal -->
<div class="modal fade" id="purchaseNumberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Purchase Virtual Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="purchaseNumberForm">
                <div class="modal-body">
                    <input type="hidden" id="purchase_number" name="number">
                    <input type="hidden" id="purchase_country_code" name="country_code">
                    <input type="hidden" id="purchase_number_type" name="number_type">
                    
                    <div class="mb-5">
                        <h6>Number: <span id="modal_number" class="text-gray-800 fw-bold"></span></h6>
                        <h6>Monthly Fee: <span id="modal_fee" class="text-gray-800 fw-bold"></span></h6>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Forward Calls To</label>
                        <input type="text" class="form-control" name="forward_to" placeholder="+1234567890">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="forward_voice" id="forward_voice" checked>
                            <label class="form-check-label" for="forward_voice">Forward Voice Calls</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="forward_sms" id="forward_sms" checked>
                            <label class="form-check-label" for="forward_sms">Forward SMS Messages</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Callback URL (Optional)</label>
                        <input type="text" class="form-control" name="callback_url" placeholder="https://example.com/callback">
                        <small class="form-text text-muted">We'll send notifications about calls and messages to this URL</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Purchase Number</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle number purchase button click
        $('.purchase-number').on('click', function() {
            const number = $(this).data('number');
            const country = $(this).data('country');
            const type = $(this).data('type');
            const fee = $(this).data('fee');
            
            $('#purchase_number').val(number);
            $('#purchase_country_code').val(country);
            $('#purchase_number_type').val(type);
            $('#modal_number').text(number);
            $('#modal_fee').text('$' + fee.toFixed(2) + '/month');
            
            $('#purchaseNumberModal').modal('show');
        });
        
        // Handle purchase form submission
        $('#purchaseNumberForm').on('submit', function(e) {
            e.preventDefault();
            
            // Submit via AJAX
            $.ajax({
                url: '{{ route("virtual-numbers.purchase") }}',
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    // Show loading state
                },
                success: function(response) {
                    if (response.success) {
                        $('#purchaseNumberModal').modal('hide');
                        
                        // Show success notification
                        toastr.success('Virtual number purchased successfully');
                        
                        // Redirect to my numbers page after short delay
                        setTimeout(function() {
                            window.location.href = '{{ route("virtual-numbers.my-numbers") }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message || 'Failed to purchase number');
                    }
                },
                error: function(xhr) {
                    // Show error notification
                    const response = xhr.responseJSON;
                    toastr.error(response?.message || 'An error occurred while processing your request');
                },
                complete: function() {
                    // Hide loading state
                }
            });
        });
        
        // Handle country and type filters
        $('#country-filter, #type-filter').on('change', function() {
            const country = $('#country-filter').val();
            const type = $('#type-filter').val();
            
            // Reload page with filters
            window.location.href = '{{ route("virtual-numbers.browse") }}' + 
                '?country=' + country + '&type=' + type;
        });
    });
</script>
@endpush