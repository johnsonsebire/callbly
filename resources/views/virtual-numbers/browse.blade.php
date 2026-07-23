@extends('layouts.master')

@section('title', 'Browse & Provision Virtual Numbers')

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
                                        <i class="ki-outline ki-phone fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Browse & Provision Numbers</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Global DIDs</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Acquire dedicated local, toll-free, and mobile virtual DID numbers for instant telephony routing.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('virtual-numbers.my-numbers') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-phone me-1 fs-5"></i> My Numbers Directory
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-home me-1 fs-5"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Number Plans Section -->
                @if(isset($plans) && $plans->count() > 0)
                <div class="card border-0 shadow-sm overflow-hidden mb-6" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Available Number Tier Plans</span>
                            <span class="text-gray-400 mt-1 fs-7">Choose a subscription tier for your virtual DID lines</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-4">
                            @foreach($plans as $plan)
                            <div class="col-md-4">
                                <div class="card border border-gray-200 shadow-xs h-100 p-5" style="border-radius: 1rem; border-top: 3px solid #0284c7 !important;">
                                    <h4 class="font-extrabold text-gray-900 fs-5 mb-1">{{ $plan->name }}</h4>
                                    <p class="text-gray-500 fs-8 mb-4">{{ $plan->description }}</p>
                                    <div class="d-flex align-items-baseline mb-4 p-3 bg-light-soft rounded-3">
                                        <span class="fs-2hx font-extrabold text-primary">{{ $plan->currency->symbol }}{{ number_format($plan->price, 2) }}</span>
                                        <span class="text-gray-500 font-medium fs-7 ms-1">/ {{ $plan->billing_cycle }}</span>
                                    </div>
                                    <ul class="list-unstyled mb-6 d-flex flex-column gap-2">
                                        @if($plan->features)
                                            @foreach(json_decode($plan->features) as $feature)
                                            <li class="d-flex align-items-center text-gray-700 font-medium fs-7">
                                                <i class="ki-outline ki-check-circle fs-5 text-success me-2"></i>
                                                <span>{{ $feature }}</span>
                                            </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    <div class="mt-auto">
                                        <button class="btn btn-primary font-bold w-100 py-2.5 select-plan" data-plan-id="{{ $plan->id }}">
                                            Select Plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Available Inventory Section -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <div class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Available Virtual Numbers</span>
                            <span class="text-gray-400 mt-1 fs-7">Select an available number to provision to your account</span>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select form-select-solid rounded-3 fs-7" id="country-filter">
                                    <option value="">All Countries</option>
                                    <option value="US" {{ request('country') === 'US' ? 'selected' : '' }}>United States</option>
                                    <option value="CA" {{ request('country') === 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="GB" {{ request('country') === 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="NG" {{ request('country') === 'NG' ? 'selected' : '' }}>Nigeria</option>
                                </select>
                                <select class="form-select form-select-solid rounded-3 fs-7" id="type-filter">
                                    <option value="">All Types</option>
                                    <option value="local" {{ request('type') === 'local' ? 'selected' : '' }}>Local</option>
                                    <option value="tollfree" {{ request('type') === 'tollfree' ? 'selected' : '' }}>Toll-Free</option>
                                    <option value="mobile" {{ request('type') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                        <th class="min-w-160px">Number</th>
                                        <th class="min-w-120px">Country</th>
                                        <th class="min-w-120px">Number Type</th>
                                        <th class="min-w-130px">Monthly Fee</th>
                                        <th class="min-w-120px text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($availableNumbers as $number)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40px me-3">
                                                    <span class="symbol-label bg-light-primary text-primary rounded-circle">
                                                        <i class="ki-outline ki-phone fs-3"></i>
                                                    </span>
                                                </div>
                                                <span class="font-mono text-gray-900 font-bold fs-6">{{ $number->number }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-secondary font-bold fs-8 px-2.5 py-1">
                                                {{ $number->country_code }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $number->number_type === 'tollfree' ? 'success' : 'primary' }} font-bold fs-8 px-2.5 py-1">
                                                {{ ucfirst($number->number_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="font-mono text-gray-800 font-bold fs-7">
                                                {{ $number->currency->symbol ?? '$' }}{{ number_format($number->monthly_fee, 2) }} / mo
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-primary btn-sm font-bold shadow-xs px-4 purchase-number" 
                                                data-number="{{ $number->number }}"
                                                data-country="{{ $number->country_code }}"
                                                data-type="{{ $number->number_type }}"
                                                data-fee="{{ $number->monthly_fee }}">
                                                <i class="ki-outline ki-shopping-cart fs-5 me-1"></i> Purchase
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                                <i class="ki-outline ki-phone fs-3hx text-primary"></i>
                                            </div>
                                            <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Numbers Matching Filter</h3>
                                            <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-4">Try clearing country or type filters to view available inventory.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-6">
                            <div class="text-gray-500 font-medium fs-7">
                                Showing {{ $availableNumbers->firstItem() ?? 0 }} to {{ $availableNumbers->lastItem() ?? 0 }} of {{ $availableNumbers->total() }} available numbers
                            </div>
                            <div>
                                {{ $availableNumbers->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Purchase Number Modal -->
<div class="modal fade" id="purchaseNumberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-header border-0 pt-6 px-6">
                <h3 class="font-extrabold text-gray-900 fs-4 mb-0">Purchase Virtual Number</h3>
                <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-2"></i>
                </button>
            </div>
            <form id="purchaseNumberForm">
                <div class="modal-body p-6">
                    <input type="hidden" id="purchase_number" name="number">
                    <input type="hidden" id="purchase_country_code" name="country_code">
                    <input type="hidden" id="purchase_number_type" name="number_type">
                    
                    <div class="d-flex align-items-center justify-content-between p-4 bg-light-soft rounded-3 mb-5 border border-gray-200">
                        <div>
                            <span class="text-gray-500 font-bold fs-8 uppercase d-block mb-0.5">Target Number</span>
                            <span id="modal_number" class="font-mono text-gray-900 font-extrabold fs-5"></span>
                        </div>
                        <div class="text-end">
                            <span class="text-gray-500 font-bold fs-8 uppercase d-block mb-0.5">Monthly Cost</span>
                            <span id="modal_fee" class="font-mono text-primary font-extrabold fs-5"></span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label font-bold text-gray-800 fs-7">Forward Calls To Number</label>
                        <input type="text" class="form-control form-control-solid rounded-3" name="forward_to" placeholder="+1234567890">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-switch bg-light-soft p-4 rounded-3 border border-gray-200">
                            <input class="form-check-input me-3" type="checkbox" name="forward_voice" id="forward_voice" checked>
                            <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="forward_voice">
                                Enable Voice Call Forwarding
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check form-switch bg-light-soft p-4 rounded-3 border border-gray-200">
                            <input class="form-check-input me-3" type="checkbox" name="forward_sms" id="forward_sms" checked>
                            <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="forward_sms">
                                Enable SMS Forwarding
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label font-bold text-gray-800 fs-7">Webhook Callback URL <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="text" class="form-control form-control-solid rounded-3" name="callback_url" placeholder="https://yourdomain.com/webhooks/voice">
                        <small class="text-gray-400 fs-8 mt-1 d-block">We'll post realtime event notifications to this endpoint.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-6 px-6">
                    <button type="button" class="btn btn-light font-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-bold px-6">
                        <i class="ki-outline ki-check me-1 fs-5"></i> Confirm & Purchase
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.purchase-number').on('click', function() {
            const number = $(this).data('number');
            const country = $(this).data('country');
            const type = $(this).data('type');
            const fee = $(this).data('fee');
            
            $('#purchase_number').val(number);
            $('#purchase_country_code').val(country);
            $('#purchase_number_type').val(type);
            $('#modal_number').text(number);
            $('#modal_fee').text('$' + fee.toFixed(2) + '/mo');
            
            $('#purchaseNumberModal').modal('show');
        });
        
        $('#purchaseNumberForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("virtual-numbers.purchase") }}',
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#purchaseNumberModal').modal('hide');
                        if (typeof toastr !== 'undefined') toastr.success('Virtual number purchased successfully');
                        
                        setTimeout(function() {
                            window.location.href = '{{ route("virtual-numbers.my-numbers") }}';
                        }, 1500);
                    } else {
                        if (typeof toastr !== 'undefined') toastr.error(response.message || 'Failed to purchase number');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (typeof toastr !== 'undefined') toastr.error(response?.message || 'An error occurred while processing your request');
                }
            });
        });
        
        $('#country-filter, #type-filter').on('change', function() {
            const country = $('#country-filter').val();
            const type = $('#type-filter').val();
            
            window.location.href = '{{ route("virtual-numbers.browse") }}' + 
                '?country=' + country + '&type=' + type;
        });
    });
</script>
@endpush