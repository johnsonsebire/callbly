@extends('layouts.master')

@section('title', 'Wallet Dashboard & Billing')

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
                                        <i class="ki-outline ki-wallet fs-2x text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <h1 class="text-white font-extrabold fs-2x mb-0">Financial Wallet & Billing</h1>
                                        <span class="badge badge-light-primary fs-8 font-bold px-2.5 py-1">Account Balance</span>
                                    </div>
                                    <p class="text-gray-400 fs-7 mb-0">Manage account funds, top-up balances, purchase SMS credits, and track transaction history.</p>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('wallet.topup') }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                    <i class="ki-outline ki-plus me-1 fs-5"></i> Top Up Balance
                                </a>
                                <a href="{{ route('wallet.purchase-sms') }}" class="btn btn-light-primary btn-sm px-4">
                                    <i class="ki-outline ki-message-text-2 me-1 fs-5"></i> Buy SMS Credits
                                </a>
                                <a href="{{ route('sms.credits') }}" class="btn btn-outline btn-outline-secondary text-gray-300 btn-sm px-4">
                                    <i class="ki-outline ki-purchase me-1 fs-5"></i> SMS Packages
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 g-xl-8">
                    <!-- Wallet Balance Card Column -->
                    <div class="col-xl-4">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #10b981 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-4">Current Wallet Balance</span>
                                    <span class="text-gray-400 mt-1 fs-7">Available account funds for broadcasts & features</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-6">
                                <div class="p-4 bg-light-success rounded-3 mb-6 border border-success/20">
                                    <span class="text-gray-600 font-bold fs-8 uppercase tracking-wider d-block mb-1">Available Funds</span>
                                    <h2 class="fs-2hx font-extrabold text-gray-900 mb-0">
                                        {{ $user->currency->symbol }}{{ number_format($wallet->balance, 2) }}
                                    </h2>
                                </div>
                                <div class="d-flex flex-column gap-3">
                                    <a href="{{ route('wallet.topup') }}" class="btn btn-primary font-bold py-3 shadow-sm">
                                        <i class="ki-outline ki-plus-circle fs-4 me-2"></i> Top Up Wallet Balance
                                    </a>
                                    <a href="{{ route('wallet.purchase-sms') }}" class="btn btn-light-primary font-bold py-3">
                                        <i class="ki-outline ki-message-text-2 fs-4 me-2"></i> Purchase SMS Credits
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction History Column -->
                    <div class="col-xl-8">
                        <div class="card border-0 shadow-sm overflow-hidden mb-5 mb-xl-8" style="border-top: 4px solid #0284c7 !important; border-radius: 1.25rem;">
                            <div class="card-header pt-6 pb-2 border-0">
                                <h3 class="card-title flex-column">
                                    <span class="card-label font-bold text-gray-900 fs-3">Transaction History</span>
                                    <span class="text-gray-400 mt-1 fs-7">Recent credits, debits, and wallet top-up logs</span>
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                @if($transactions->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-gray-400 fs-7 uppercase">
                                                    <th class="min-w-140px">Date & Time</th>
                                                    <th class="min-w-200px">Description</th>
                                                    <th class="min-w-100px">Type</th>
                                                    <th class="min-w-120px">Amount</th>
                                                    <th class="min-w-110px text-end">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td>
                                                            <span class="text-gray-800 font-semibold fs-7">
                                                                {{ $transaction->created_at->format('M d, Y H:i') }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="text-gray-900 font-bold fs-7 d-block">
                                                                {{ $transaction->description }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($transaction->type == 'credit')
                                                                <span class="badge badge-light-success font-bold fs-8 px-2.5 py-1">Credit</span>
                                                            @else
                                                                <span class="badge badge-light-danger font-bold fs-8 px-2.5 py-1">Debit</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="font-mono text-gray-900 font-bold fs-7">
                                                                {{ $user->currency->symbol }}{{ number_format($transaction->amount, 2) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            @if($transaction->status == 'completed')
                                                                <span class="badge badge-light-success font-bold fs-8 px-2.5 py-1 rounded-pill">Completed</span>
                                                            @elseif($transaction->status == 'pending')
                                                                <span class="badge badge-light-warning font-bold fs-8 px-2.5 py-1 rounded-pill">Pending</span>
                                                            @else
                                                                <span class="badge badge-light-danger font-bold fs-8 px-2.5 py-1 rounded-pill">Failed</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-center mt-6">
                                        {{ $transactions->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <div class="symbol symbol-70px symbol-circle bg-light-primary mb-4 p-3">
                                            <i class="ki-outline ki-wallet fs-3hx text-primary"></i>
                                        </div>
                                        <h3 class="fs-2 font-extrabold text-gray-900 mb-2">No Transactions Found</h3>
                                        <p class="text-gray-400 fs-6 max-w-500px mx-auto mb-6">Your wallet transaction history will be recorded here when you top up funds or purchase credits.</p>
                                        <a href="{{ route('wallet.topup') }}" class="btn btn-primary font-bold px-6 shadow-sm">
                                            <i class="ki-outline ki-plus fs-4 me-2"></i>Top Up Your Wallet
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection