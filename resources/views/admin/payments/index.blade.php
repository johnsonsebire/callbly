@extends('layouts.master')

@section('title', 'Payment Management')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Payment Management
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Admin</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Payment Management</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-payment-table-filter="search" 
                                   class="form-control form-control-solid w-250px ps-13" 
                                   placeholder="Search by reference or user..." 
                                   value="{{ request('search') }}" />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-payment-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="ki-outline ki-filter fs-2"></i>Filter
                            </button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">Filter Options</div>
                                </div>
                                <div class="separator border-gray-200"></div>
                                <form method="GET" action="{{ route('admin.payments.index') }}">
                                    <div class="px-7 py-5" data-kt-payment-table-filter="form">
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Status:</label>
                                            <select class="form-select form-select-solid fw-bold" name="status" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true">
                                                <option></option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                            </select>
                                        </div>
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Date From:</label>
                                            <input class="form-control form-control-solid" name="date_from" type="date" value="{{ request('date_from') }}" />
                                        </div>
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Date To:</label>
                                            <input class="form-control form-control-solid" name="date_to" type="date" value="{{ request('date_to') }}" />
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true">Reset</button>
                                            <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true">Apply</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                        </div>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body py-4">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                            <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success">Success</h4>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                            <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-danger">Error</h4>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!--begin::Stats Cards-->
                    <div class="row g-6 g-xl-9 mb-6" id="payment-stats">
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-md-50 mb-5 mb-xl-10">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6">Pending Payments</div>
                                        <i class="ki-outline ki-hourglass-3 fs-2x text-warning"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bold fs-2" id="pending-count">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-md-50 mb-5 mb-xl-10">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6">Pending Amount</div>
                                        <i class="ki-outline ki-dollar fs-2x text-warning"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bold fs-2" id="pending-amount">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-md-50 mb-5 mb-xl-10">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6">Failed Payments</div>
                                        <i class="ki-outline ki-cross-circle fs-2x text-danger"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bold fs-2" id="failed-count">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-md-50 mb-5 mb-xl-10">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex flex-stack">
                                        <div class="text-gray-700 fw-semibold fs-6">Today Confirmed</div>
                                        <i class="ki-outline ki-check-circle fs-2x text-success"></i>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bold fs-2" id="today-confirmed">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Stats Cards-->

                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_payment_table">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Reference</th>
                                    <th class="min-w-125px">User</th>
                                    <th class="min-w-100px">Amount</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-125px">Date</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $transaction->reference }}</span>
                                                @if($transaction->description)
                                                    <span class="text-muted fs-7">{{ Str::limit($transaction->description, 50) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ $transaction->user->name }}</span>
                                                <span class="text-muted fs-7">{{ $transaction->user->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-success fw-bold">
                                                ₦{{ number_format($transaction->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->status === 'pending')
                                                <span class="badge badge-light-warning">Pending</span>
                                            @elseif($transaction->status === 'failed')
                                                <span class="badge badge-light-danger">Failed</span>
                                            @else
                                                <span class="badge badge-light-success">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $transaction->created_at->format('M d, Y') }}</span>
                                                <span class="text-muted fs-7">{{ $transaction->created_at->format('h:i A') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Actions
                                                <i class="ki-outline ki-down fs-5 ms-1"></i>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.payments.show', $transaction->id) }}" class="menu-link px-3">View Details</a>
                                                </div>
                                                @if(in_array($transaction->status, ['pending', 'failed']))
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 text-success" onclick="confirmPayment({{ $transaction->id }})">Confirm Payment</a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3 text-danger" onclick="rejectPayment({{ $transaction->id }})">Reject Payment</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-4"></i>
                                                <span class="text-muted fs-6">No pending or failed payments found</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->

                    <!--begin::Pagination-->
                    @if($transactions->hasPages())
                        <div class="row">
                            <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="dataTables_info">
                                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} results
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                {{ $transactions->links() }}
                            </div>
                        </div>
                    @endif
                    <!--end::Pagination-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>
    <!--end::Content-->
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Confirm Payment</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form id="confirmPaymentForm" method="POST">
                @csrf
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Confirmation Note (Optional)</label>
                        <textarea class="form-control form-control-solid mb-3 mb-lg-0" name="confirmation_note" rows="3" placeholder="Add any notes about this payment confirmation..."></textarea>
                    </div>
                    <div class="text-center pt-10">
                        <p class="text-muted">Are you sure you want to confirm this payment? This will credit the user's wallet and mark the transaction as completed.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <span class="indicator-label">Confirm Payment</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Payment Modal -->
<div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Reject Payment</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form id="rejectPaymentForm" method="POST">
                @csrf
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Rejection Reason</label>
                        <textarea class="form-control form-control-solid mb-3 mb-lg-0" name="rejection_reason" rows="3" placeholder="Please provide a reason for rejecting this payment..." required></textarea>
                    </div>
                    <div class="text-center pt-10">
                        <p class="text-muted">Are you sure you want to reject this payment? This action cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <span class="indicator-label">Reject Payment</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Load payment stats
function loadPaymentStats() {
    fetch('{{ route("admin.payments.stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pending-count').textContent = data.pending_count;
            document.getElementById('pending-amount').textContent = '₦' + parseFloat(data.pending_amount).toLocaleString();
            document.getElementById('failed-count').textContent = data.failed_count;
            document.getElementById('today-confirmed').textContent = data.today_confirmed;
        })
        .catch(error => {
            console.error('Error loading stats:', error);
        });
}

// Search functionality
document.querySelector('[data-kt-payment-table-filter="search"]').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const searchValue = this.value;
        const url = new URL(window.location);
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    }
});

// Confirm payment function
function confirmPayment(transactionId) {
    const modal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
    const form = document.getElementById('confirmPaymentForm');
    form.action = `{{ route('admin.payments.index') }}/${transactionId}/confirm`;
    modal.show();
}

// Reject payment function
function rejectPayment(transactionId) {
    const modal = new bootstrap.Modal(document.getElementById('rejectPaymentModal'));
    const form = document.getElementById('rejectPaymentForm');
    form.action = `{{ route('admin.payments.index') }}/${transactionId}/reject`;
    modal.show();
}

// Load stats on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPaymentStats();
});

// Form submission indicators
document.getElementById('confirmPaymentForm').addEventListener('submit', function() {
    const button = this.querySelector('button[type="submit"]');
    button.setAttribute('data-kt-indicator', 'on');
    button.disabled = true;
});

document.getElementById('rejectPaymentForm').addEventListener('submit', function() {
    const button = this.querySelector('button[type="submit"]');
    button.setAttribute('data-kt-indicator', 'on');
    button.disabled = true;
});

// Reset filter form
document.querySelector('[type="reset"]').addEventListener('click', function() {
    window.location.href = '{{ route("admin.payments.index") }}';
});
</script>
@endpush
