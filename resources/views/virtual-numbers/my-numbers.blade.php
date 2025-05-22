@extends('layouts.master')

@section('title', 'My Virtual Numbers')

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
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h4 class="fw-bold">My Virtual Numbers</h4>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i> Get New Number
                                    </a>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if($numbers->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-125px">Number</th>
                                                    <th class="min-w-125px">Type</th>
                                                    <th class="min-w-125px">Monthly Fee</th>
                                                    <th class="min-w-125px">Status</th>
                                                    <th class="min-w-125px">Expires At</th>
                                                    <th class="min-w-125px">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($numbers as $number)
                                                    <tr>
                                                        <td>{{ $number->number }}</td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $number->type === 'toll-free' ? 'success' : 'primary' }}">
                                                                {{ ucfirst($number->type) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ auth()->user()->currency->symbol }}{{ number_format($number->monthly_fee, 2) }}</td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $number->status === 'active' ? 'success' : ($number->status === 'expired' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($number->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $number->expires_at ? $number->expires_at->format('M d, Y') : 'N/A' }}</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#configureModal{{ $number->id }}">
                                                                            <i class="bi bi-gear me-2"></i> Configure
                                                                        </a>
                                                                    </li>
                                                                    @if($number->status === 'active')
                                                                        <li>
                                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#renewModal{{ $number->id }}">
                                                                                <i class="bi bi-arrow-clockwise me-2"></i> Renew
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    <li>
                                                                        <a class="dropdown-item" href="#" onclick="viewUsage('{{ $number->id }}')">
                                                                            <i class="bi bi-graph-up me-2"></i> View Usage
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <!-- Configure Modal -->
                                                    <div class="modal fade" id="configureModal{{ $number->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Configure Number</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="configureForm{{ $number->id }}">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Forward Calls To</label>
                                                                            <input type="tel" class="form-control" name="forwarding_number" 
                                                                                value="{{ $number->forwarding_number }}" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="checkbox" name="forward_sms" 
                                                                                    id="forwardSms{{ $number->id }}" 
                                                                                    {{ $number->forward_sms ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="forwardSms{{ $number->id }}">
                                                                                    Forward SMS Messages
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="button" class="btn btn-primary" onclick="saveConfiguration('{{ $number->id }}')">
                                                                        Save Changes
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Renew Modal -->
                                                    <div class="modal fade" id="renewModal{{ $number->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Renew Virtual Number</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="renewForm{{ $number->id }}">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Select Plan</label>
                                                                            <select class="form-select" name="plan_id" required>
                                                                                @foreach(App\Models\ServicePlan::ofType('virtual_number')->active()->get() as $plan)
                                                                                    <option value="{{ $plan->id }}">
                                                                                        {{ $plan->name }} - {{ auth()->user()->currency->symbol }}{{ number_format($plan->price, 2) }}/{{ $plan->duration }} days
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Payment Method</label>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                                                    id="walletPayment{{ $number->id }}" value="wallet" checked>
                                                                                <label class="form-check-label" for="walletPayment{{ $number->id }}">
                                                                                    Pay from Wallet (Balance: {{ auth()->user()->currency->symbol }}{{ number_format(auth()->user()->wallet->balance, 2) }})
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                                                    id="cardPayment{{ $number->id }}" value="card">
                                                                                <label class="form-check-label" for="cardPayment{{ $number->id }}">
                                                                                    Pay with Card
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="button" class="btn btn-primary" onclick="renewNumber('{{ $number->id }}')">
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
                                    <div class="mt-4">
                                        {{ $numbers->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="bi bi-telephone-plus fs-1 text-muted" style="font-size: 5rem !important;"></i>
                                        </div>
                                        <h5>No Virtual Numbers Yet</h5>
                                        <p class="text-muted">You haven't purchased any virtual numbers yet.</p>
                                        <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary">
                                            Browse Available Numbers
                                        </a>
                                    </div>
                                @endif
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
                toastr.success('Number configuration updated successfully');
                $('#configureModal' + numberId).modal('hide');
            } else {
                toastr.error(response.message || 'Failed to update configuration');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            toastr.error(response?.message || 'An error occurred while updating configuration');
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
                toastr.success('Number renewed successfully');
                $('#renewModal' + numberId).modal('hide');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                toastr.error(response.message || 'Failed to renew number');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            toastr.error(response?.message || 'An error occurred while processing renewal');
        }
    });
}

function viewUsage(numberId) {
    window.location.href = `/virtual-numbers/${numberId}/usage`;
}
</script>
@endpush
@endsection