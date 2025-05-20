<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><h5 class="mb-0">SMS Billing Tier</h5></div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif

                    <div class="alert alert-info">
                        <h5>Your Current Tier: <strong>{{ $currentTier->name }}</strong></h5>
                        <p>You are currently on the <strong>{{ $currentTier->name }}</strong> tier with a 
                           price of <strong>{{ $formattedSmsRate }}</strong> per SMS.</p>
                    </div><div class="card-title"></div><div class="card-title"></div>

                    <h5 class="mt-4 mb-3">Available SMS Billing Tiers</h5>
                    <p>As you purchase more SMS credits, you'll automatically be upgraded to higher tiers with better rates.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tier</th>
                                    <th>Price per SMS (GHS)</th>
                                    <th>Price per SMS ({{ $userCurrency->code }})</th>
                                    <th>Minimum Purchase</th>
                                    <th>Maximum Purchase</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allTiers as $tier)
                                <tr class="{{ $tier->id === $currentTier->id ? 'table-primary' : '' }}">
                                    <td><strong>{{ $tier->name }}</strong></td>
                                    <td>₵{{ number_format($tier->price_per_sms, 3) }}</td>
                                    <td>{{ $userCurrency->symbol }}{{ number_format($tier->price_per_sms * $userCurrency->exchange_rate, 3) }}</td>
                                    <td>₵{{ number_format($tier->min_purchase, 2) }}</td>
                                    <td>
                                        @if($tier->max_purchase)
                                            ₵{{ number_format($tier->max_purchase, 2) }}
                                        @else
                                            Unlimited
                                        @endif
                                    </td>
                                    <td>{{ $tier->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class="card-title"><h5 class="mb-0">How Billing Tiers Work</h5></div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Tier Qualification</h6>
                            <ul>
                                <li>All users start on the Basic tier</li>
                                <li>Purchasing ₵1,500 - ₵2,999 worth of SMS qualifies you for the Plus tier</li>
                                <li>Purchasing ₵3,000 - ₵5,999 worth of SMS qualifies you for the Premium tier</li>
                                <li>Purchases of ₵6,000 or more qualify you for the Gold tier</li>
                            </ul>
                        </div><div class="card-title"></div><div class="card-title"></div>
                        <div class="col-md-6">
                            <h6>Tier Benefits</h6>
                            <ul>
                                <li>Higher tiers offer lower per-SMS rates</li>
                                <li>Tier upgrades happen automatically based on your purchase volume</li>
                                <li>Once upgraded, you maintain your tier level for future purchases</li>
                                <li>Tier pricing applies to all your SMS sending</li>
                            </ul>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    
                    <div class="alert alert-warning mt-3">
                        <strong>Note:</strong> When making purchases on higher tiers, there are minimum purchase requirements:
                        <ul class="mb-0 mt-2">
                            <li>Plus tier: Minimum purchase of ₵1,500</li>
                            <li>Premium tier: Minimum purchase of ₵3,000</li>
                            <li>Gold tier: Minimum purchase of ₵6,000</li>
                        </ul>
                    </div><div class="card-title"></div><div class="card-title"></div>
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection