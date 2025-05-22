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
                        <h2>SMS Credits</h2>
                        <p class="text-muted">Manage your SMS credits and purchase history</p>
                    </div>
                </div>

                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-md-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <div class="card-title">
                                    <h2 class="fw-bold">Current Balance</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex justify-content-center align-items-center mb-3">
                                        <h1 class="display-1 fw-bold text-primary">{{ $smsCredits ?? 0 }}</h1>
                                    </div>
                                    <p class="text-muted fs-6">Available SMS Credits</p>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buyCreditsModal">
                                            Buy More Credits
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>

                    <div class="col-md-6">
                        <!--begin::Card-->
                        <div class="card card-flush h-100">
                            <!--begin::Card header-->
                            <div class="card-header pt-7">
                                <div class="card-title">
                                    <h2 class="fw-bold">SMS Pricing</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bold text-muted">
                                                <th>Credits</th>
                                                <th>Price ({{ $currency->code }})</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>500</td>
                                                <td>{{ number_format(500 * ($currency->conversion_rate ?? 1) * 0.01, 2) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary buy-credits" data-amount="500">Buy</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1,000</td>
                                                <td>{{ number_format(1000 * ($currency->conversion_rate ?? 1) * 0.01, 2) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary buy-credits" data-amount="1000">Buy</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5,000</td>
                                                <td>{{ number_format(5000 * ($currency->conversion_rate ?? 1) * 0.01, 2) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary buy-credits" data-amount="5000">Buy</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10,000</td>
                                                <td>{{ number_format(10000 * ($currency->conversion_rate ?? 1) * 0.009, 2) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary buy-credits" data-amount="10000">Buy</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                    <h2 class="fw-bold">Purchase History</h2>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if($creditPurchases->isEmpty())
                                    <p class="text-center my-4">You haven't purchased any credits yet.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Credits</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th>Reference</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($creditPurchases as $purchase)
                                                    <tr>
                                                        <td>{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                                                        <td>{{ $purchase->currency->symbol }} {{ number_format($purchase->amount, 2) }}</td>
                                                        <td>{{ number_format($purchase->credits) }}</td>
                                                        <td>{{ ucfirst($purchase->payment_method) }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $purchase->status == 'completed' ? 'success' : ($purchase->status == 'failed' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($purchase->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $purchase->reference }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
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
                                    <h2 class="fw-bold">Recent Usage</h2>
                                </div>
                                <div class="card-toolbar">
                                    <a href="{{ route('sms.campaigns') }}" class="btn btn-sm btn-link">View All Campaigns</a>
                                </div>
                            </div>
                            <!--end::Card header-->
                            
                            <!--begin::Card body-->
                            <div class="card-body py-5">
                                @if($campaigns->isEmpty())
                                    <p class="text-center my-4">You haven't used any credits yet.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                            <thead>
                                                <tr class="fw-bold text-muted">
                                                    <th>Campaign</th>
                                                    <th>Date</th>
                                                    <th>Credits Used</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($campaigns as $campaign)
                                                    <tr>
                                                        <td>{{ \Illuminate\Support\Str::limit($campaign->name, 30) }}</td>
                                                        <td>{{ $campaign->created_at->format('M d, Y H:i') }}</td>
                                                        <td>{{ number_format($campaign->credits_used) }}</td>
                                                        <td>
                                                            <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="btn btn-sm btn-outline-primary">
                                                                View Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

<!-- Buy Credits Modal -->
<div class="modal fade" id="buyCreditsModal" tabindex="-1" aria-labelledby="buyCreditsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyCreditsModalLabel">Buy SMS Credits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="buyCreditForm" action="{{ route('sms.buy-credits') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="creditAmount" class="form-label">Credit Amount</label>
                        <input type="number" class="form-control" id="creditAmount" name="credit_amount" value="1000" min="100">
                        <div class="form-text">Enter the number of credits you want to purchase</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $currency->symbol }}</span>
                            <input type="text" class="form-control" id="creditPrice" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod" name="payment_method">
                            <option value="card">Credit Card</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="ussd">USSD</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="buyCreditForm" class="btn btn-primary">Proceed to Payment</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const conversionRate = {{ $currency->conversion_rate ?? 1 }};
        const currencySymbol = '{{ $currency->symbol }}';
        
        // Calculate initial price
        calculatePrice();
        
        // Update price when credit amount changes
        document.getElementById('creditAmount').addEventListener('input', calculatePrice);
        
        // Set credit amount when clicking on buy buttons in the pricing table
        document.querySelectorAll('.buy-credits').forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.getAttribute('data-amount');
                document.getElementById('creditAmount').value = amount;
                calculatePrice();
                document.getElementById('buyCreditsModal').classList.add('show');
                document.getElementById('buyCreditsModal').style.display = 'block';
            });
        });
        
        function calculatePrice() {
            const credits = document.getElementById('creditAmount').value;
            let rate = 0.01; // Base rate
            
            if (credits >= 10000) {
                rate = 0.009; // Discounted rate for larger purchases
            }
            
            const price = (credits * rate * conversionRate).toFixed(2);
            document.getElementById('creditPrice').value = price;
        }
    });
</script>
@endsection