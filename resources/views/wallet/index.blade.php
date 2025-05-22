@extends('layouts.master')

@section('title', 'Wallet Dashboard')

@section('content')
<div class="app-main flex-column flex-row-fluid">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xl-4">
                        <!--begin::Card-->
                        <div class="card card-flush mb-5">
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <h5 class="card-title fw-bold mb-5">Wallet Balance</h5>
                                <h2 class="display-6 mb-4">{{ $user->currency->symbol }}{{ number_format($wallet->balance, 2) }}</h2>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('wallet.topup') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i> Top Up Wallet
                                    </a>
                                    <a href="{{ route('wallet.purchase-sms') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-chat-dots me-2"></i> Purchase SMS Credits
                                    </a>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    
                    <div class="col-xl-8">
                        <!--begin::Card-->
                        <div class="card card-flush mb-5">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title">
                                    <h2 class="fw-bold">Transaction History</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if($transactions->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th class="min-w-125px">Date</th>
                                                    <th class="min-w-125px">Description</th>
                                                    <th class="min-w-125px">Type</th>
                                                    <th class="min-w-125px">Amount</th>
                                                    <th class="min-w-125px">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                                        <td>{{ $transaction->description }}</td>
                                                        <td>
                                                            @if($transaction->type == 'credit')
                                                                <span class="badge badge-light-success">Credit</span>
                                                            @else
                                                                <span class="badge badge-light-danger">Debit</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $user->currency->symbol }}{{ number_format($transaction->amount, 2) }}</td>
                                                        <td>
                                                            @if($transaction->status == 'completed')
                                                                <span class="badge badge-light-success">Completed</span>
                                                            @elseif($transaction->status == 'pending')
                                                                <span class="badge badge-light-warning">Pending</span>
                                                            @else
                                                                <span class="badge badge-light-danger">Failed</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4">
                                        {{ $transactions->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="text-center mb-3">
                                            <i class="bi bi-wallet2 text-muted" style="font-size: 5rem;"></i>
                                        </div>
                                        <h5>No Transactions Yet</h5>
                                        <p class="text-muted">Your wallet transactions will appear here once you start using your wallet.</p>
                                        <a href="{{ route('wallet.topup') }}" class="btn btn-primary">Top Up Your Wallet</a>
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
@endsection