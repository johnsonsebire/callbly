@extends('layouts.master')

@section('title', 'My Virtual Numbers')

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
                                        <h1 class="text-white font-extrabold fs-2x mb-0">My Virtual Numbers</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Dedicated DIDs</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Manage active dedicated DID lines, call forwarding routing, SMS triggers, and plan renewals.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Get New Number
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-home me-1 fs-5"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-title flex-column">
                            <span class="card-label font-bold text-gray-900 fs-3">Active Virtual Lines</span>
                            <span class="text-gray-400 mt-1 fs-7">List of purchased telephone numbers assigned to your organization</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        @if($numbers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                            <th class="min-w-150px">Virtual Number</th>
                                            <th class="min-w-120px">Line Type</th>
                                            <th class="min-w-120px">Monthly Fee</th>
                                            <th class="min-w-110px">Status</th>
                                            <th class="min-w-130px">Expires At</th>
                                            <th class="min-w-140px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($numbers as $number)
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
                                                    <span class="badge badge-light-{{ $number->type === 'toll-free' ? 'success' : 'primary' }} font-bold fs-8 px-2.5 py-1">
                                                        {{ ucfirst($number->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-mono text-gray-800 font-bold fs-7">
                                                        {{ auth()->user()->currency->symbol }}{{ number_format($number->monthly_fee, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = match(strtolower($number->status)) {
                                                            'active' => 'badge-light-success',
                                                            'expired' => 'badge-light-danger',
                                                            default => 'badge-light-warning'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $statusClass }} font-bold fs-8 px-2.5 py-1 rounded-pill">
                                                        {{ ucfirst($number->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-gray-800 font-semibold fs-7">
                                                        {{ $number->expires_at ? $number->expires_at->format('M d, Y') : 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-icon btn-light-primary rounded-circle" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#configureModal{{ $number->id }}"
                                                                title="Configure Forwarding">
                                                            <i class="ki-outline ki-setting-2 fs-4"></i>
                                                        </button>
                                                        @if($number->status === 'active')
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-icon btn-light-success rounded-circle" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#renewModal{{ $number->id }}"
                                                                    title="Renew Plan">
                                                                <i class="ki-outline ki-arrows-loop fs-4"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" 
                                                                class="btn btn-sm btn-icon btn-light-info rounded-circle" 
                                                                onclick="viewUsage('{{ $number->id }}')"
                                                                title="View Usage Metrics">
                                                            <i class="ki-outline ki-chart-line-up fs-4"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Configure Modal -->
                                            <div class="modal fade" id="configureModal{{ $number->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered mw-500px">
                                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                                                        <div class="modal-header border-0 pt-6 px-6">
                                                            <h3 class="font-extrabold text-gray-900 fs-4 mb-0">Configure Line Routing</h3>
                                                            <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle" data-bs-dismiss="modal">
                                                                <i class="ki-outline ki-cross fs-2"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-6">
                                                            <form id="configureForm{{ $number->id }}">
                                                                <div class="mb-4">
                                                                    <label class="form-label font-bold text-gray-800 fs-7">Forward Calls To Number</label>
                                                                    <input type="tel" class="form-control form-control-solid rounded-3" name="forwarding_number" 
                                                                        value="{{ $number->forwarding_number }}" placeholder="+1 (555) 000-0000" required />
                                                                </div>
                                                                <div class="mb-2">
                                                                    <div class="form-check bg-light-soft p-4 rounded-3 border border-gray-200">
                                                                        <input class="form-check-input" type="checkbox" name="forward_sms" 
                                                                            id="forwardSms{{ $number->id }}" 
                                                                            {{ $number->forward_sms ? 'checked' : '' }}>
                                                                        <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="forwardSms{{ $number->id }}">
                                                                            Forward SMS Messages to Mobile
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0 pb-6 px-6">
                                                            <button type="button" class="btn btn-light font-bold" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary font-bold px-5" onclick="saveConfiguration('{{ $number->id }}')">
                                                                Save Configuration
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Renew Modal -->
                                            <div class="modal fade" id="renewModal{{ $number->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered mw-550px">
                                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
                                                        <div class="modal-header border-0 pt-6 px-6">
                                                            <h3 class="font-extrabold text-gray-900 fs-4 mb-0">Renew Virtual Number Subscription</h3>
                                                            <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle" data-bs-dismiss="modal">
                                                                <i class="ki-outline ki-cross fs-2"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-6">
                                                            <form id="renewForm{{ $number->id }}">
                                                                <div class="mb-4">
                                                                    <label class="form-label font-bold text-gray-800 fs-7">Select Renewal Plan</label>
                                                                    <select class="form-select form-select-solid rounded-3" name="plan_id" required>
                                                                        @foreach(App\Models\ServicePlan::ofType('virtual_number')->active()->get() as $plan)
                                                                            <option value="{{ $plan->id }}">
                                                                                {{ $plan->name }} - {{ auth()->user()->currency->symbol }}{{ number_format($plan->price, 2) }} / {{ $plan->duration }} days
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label font-bold text-gray-800 fs-7 mb-2">Payment Method</label>
                                                                    <div class="d-flex flex-column gap-2 bg-light-soft p-4 rounded-3 border border-gray-200">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                                                id="walletPayment{{ $number->id }}" value="wallet" checked>
                                                                            <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="walletPayment{{ $number->id }}">
                                                                                Pay from Wallet (Balance: {{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }})
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                                                id="cardPayment{{ $number->id }}" value="card">
                                                                            <label class="form-check-label font-bold text-gray-800 fs-7 cursor-pointer" for="cardPayment{{ $number->id }}">
                                                                                Pay with Credit / Debit Card
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0 pb-6 px-6">
                                                            <button type="button" class="btn btn-light font-bold" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary font-bold px-6" onclick="renewNumber('{{ $number->id }}')">
                                                                Proceed to Payment
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-6">
                                {{ $numbers->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                    <i class="ki-outline ki-phone fs-3hx text-primary"></i>
                                </div>
                                <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Virtual Numbers Purchased</h3>
                                <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Get dedicated local or toll-free telephone numbers to receive customer calls and SMS messages worldwide.</p>
                                <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                    <i class="ki-outline ki-plus fs-4 me-2"></i>Browse Available Numbers
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
<script>
function saveConfiguration(numberId) {
    const form = $('#configureForm' + numberId);
    
    $.ajax({
        url: `/api/virtual-numbers/${numberId}/forwarding`,
        type: 'PUT',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                if (typeof toastr !== 'undefined') toastr.success('Number configuration updated successfully');
                $('#configureModal' + numberId).modal('hide');
            } else {
                if (typeof toastr !== 'undefined') toastr.error(response.message || 'Failed to update configuration');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (typeof toastr !== 'undefined') toastr.error(response?.message || 'An error occurred while updating configuration');
        }
    });
}

function renewNumber(numberId) {
    const form = $('#renewForm' + numberId);
    
    $.ajax({
        url: `/api/virtual-numbers/${numberId}/renew`,
        type: 'POST',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                if (typeof toastr !== 'undefined') toastr.success('Number renewed successfully');
                $('#renewModal' + numberId).modal('hide');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                if (typeof toastr !== 'undefined') toastr.error(response.message || 'Failed to renew number');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (typeof toastr !== 'undefined') toastr.error(response?.message || 'An error occurred while processing renewal');
        }
    });
}

function viewUsage(numberId) {
    window.location.href = `/virtual-numbers/${numberId}/usage`;
}
</script>
@endpush