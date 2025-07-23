@extends('layouts.master')

@section('title', 'Payment Details')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Payment Details
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
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.payments.index') }}" class="text-muted text-hover-primary">Payment Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Details</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light">
                    <i class="ki-outline ki-arrow-left fs-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="row g-5 g-xl-10">
                <!--begin::Transaction Details-->
                <div class="col-xl-8">
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="fw-bold">Transaction Information</h3>
                            </div>
                            <div class="card-toolbar">
                                @if($transaction->status === 'pending')
                                    <span class="badge badge-light-warning badge-lg">Pending</span>
                                @elseif($transaction->status === 'failed')
                                    <span class="badge badge-light-danger badge-lg">Failed</span>
                                @else
                                    <span class="badge badge-light-success badge-lg">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Reference</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $transaction->reference }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Amount</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ \App\Models\Currency::getDefaultCurrency()->format($transaction->amount) }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Type</label>
                                <div class="col-lg-8">
                                    <span class="badge badge-light-primary">{{ ucfirst($transaction->type) }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Status</label>
                                <div class="col-lg-8">
                                    @if($transaction->status === 'pending')
                                        <span class="badge badge-light-warning">Pending</span>
                                    @elseif($transaction->status === 'failed')
                                        <span class="badge badge-light-danger">Failed</span>
                                    @else
                                        <span class="badge badge-light-success">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($transaction->description)
                                <div class="row mb-7">
                                    <label class="col-lg-4 fw-semibold text-muted">Description</label>
                                    <div class="col-lg-8">
                                        <span class="fw-semibold fs-6 text-gray-800">{{ $transaction->description }}</span>
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Created At</label>
                                <div class="col-lg-8">
                                    <span class="fw-semibold fs-6 text-gray-800">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Updated At</label>
                                <div class="col-lg-8">
                                    <span class="fw-semibold fs-6 text-gray-800">{{ $transaction->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--begin::User Information-->
                    <div class="card mb-5 mb-xl-10">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="fw-bold">User Information</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Name</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-gray-800">{{ $transaction->user->name }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Email</label>
                                <div class="col-lg-8">
                                    <span class="fw-semibold fs-6 text-gray-800">{{ $transaction->user->email }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">Current Wallet Balance</label>
                                <div class="col-lg-8">
                                    <span class="fw-bold fs-6 text-success">{{ \App\Models\Currency::getDefaultCurrency()->format($transaction->wallet->balance ?? 0) }}</span>
                                </div>
                            </div>
                            <div class="row mb-7">
                                <label class="col-lg-4 fw-semibold text-muted">User Joined</label>
                                <div class="col-lg-8">
                                    <span class="fw-semibold fs-6 text-gray-800">{{ $transaction->user->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($transaction->metadata)
                        <!--begin::Metadata-->
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-bold">Transaction Metadata</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 gy-7">
                                        <tbody>
                                            @foreach($transaction->metadata as $key => $value)
                                                <tr>
                                                    <td class="fw-semibold text-muted w-200px">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                    <td class="fw-bold">
                                                        @if(is_array($value) || is_object($value))
                                                            <pre>{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Metadata-->
                    @endif
                </div>
                <!--end::Transaction Details-->

                <!--begin::Actions-->
                <div class="col-xl-4">
                    @if(in_array($transaction->status, ['pending', 'failed']))
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-bold">Actions</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-5">
                                    <button type="button" class="btn btn-success w-100" onclick="confirmPayment({{ $transaction->id }})">
                                        <i class="ki-outline ki-check fs-2"></i>
                                        Confirm Payment
                                    </button>
                                    <button type="button" class="btn btn-danger w-100" onclick="rejectPayment({{ $transaction->id }})">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                        Reject Payment
                                    </button>
                                </div>
                                <div class="separator my-6"></div>
                                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6">
                                    <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <div class="fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">Important</h4>
                                            <div class="fs-6 text-gray-700">
                                                Confirming this payment will:
                                                <ul class="mt-3">
                                                    <li>Credit {{ \App\Models\Currency::getDefaultCurrency()->format($transaction->amount) }} to the user's wallet</li>
                                                    <li>Mark the transaction as completed</li>
                                                    <li>Log the admin action for audit purposes</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-bold">Status</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="notice d-flex bg-light-success rounded border-success border border-dashed p-6">
                                    <i class="ki-outline ki-check-circle fs-2tx text-success me-4"></i>
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <div class="fw-semibold">
                                            <h4 class="text-gray-900 fw-bold">Transaction Completed</h4>
                                            <div class="fs-6 text-gray-700">
                                                This transaction has already been processed and cannot be modified.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!--begin::Recent Activity-->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="fw-bold">Recent User Transactions</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $recentTransactions = \App\Models\WalletTransaction::where('user_id', $transaction->user_id)
                                    ->where('id', '!=', $transaction->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @if($recentTransactions->count() > 0)
                                <div class="timeline">
                                    @foreach($recentTransactions as $recent)
                                        <div class="timeline-item">
                                            <div class="timeline-line w-40px"></div>
                                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                                <div class="symbol-label bg-light">
                                                    @if($recent->type === 'credit')
                                                        <i class="ki-outline ki-arrow-up fs-4 text-success"></i>
                                                    @else
                                                        <i class="ki-outline ki-arrow-down fs-4 text-danger"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="timeline-content mb-10 mt-n1">
                                                <div class="pe-3 mb-5">
                                                    <div class="fs-5 fw-semibold mb-2">
                                                        {{ $recent->type === 'credit' ? '+' : '-' }}{{ \App\Models\Currency::getDefaultCurrency()->format($recent->amount) }}
                                                    </div>
                                                    <div class="d-flex align-items-center mt-1 fs-6">
                                                        <div class="text-muted me-2 fs-7">{{ $recent->created_at->diffForHumans() }}</div>
                                                        <span class="badge badge-light-{{ $recent->status === 'completed' ? 'success' : ($recent->status === 'pending' ? 'warning' : 'danger') }} badge-circle">{{ ucfirst($recent->status) }}</span>
                                                    </div>
                                                    @if($recent->description)
                                                        <div class="text-muted fs-7 mt-1">{{ Str::limit($recent->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                    <div class="text-muted fs-6">No other transactions found</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!--end::Recent Activity-->
                </div>
                <!--end::Actions-->
            </div>
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
                        <p class="text-muted">Are you sure you want to confirm this payment of <strong>{{ \App\Models\Currency::getDefaultCurrency()->format($transaction->amount) }}</strong>? This will credit the user's wallet and mark the transaction as completed.</p>
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
                        <p class="text-muted">Are you sure you want to reject this payment of <strong>{{ \App\Models\Currency::getDefaultCurrency()->format($transaction->amount) }}</strong>? This action cannot be undone.</p>
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
</script>
@endpush
