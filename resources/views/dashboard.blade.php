@extends('layouts.master')
@section('content')
    <div class="container-fluid py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold text-dark">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-muted">Here's an overview of your account</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-sms fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">SMS Balance</h6>
                                <h3 class="mb-0">{{ $smsBalance ?? 0 }} credits</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-mobile-alt fa-2x text-success"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">USSD Balance</h6>
                                <h3 class="mb-0">{{ $ussdBalance ?? 0 }} credits</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">Active Contacts</h6>
                                <h3 class="mb-0">{{ $activeContacts ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-wallet fa-2x text-warning"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="card-title mb-1">Wallet Balance</h6>
                                <h3 class="mb-0">{{ $balance }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-12 col-xl-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Recipient</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentActivities ?? [] as $activity)
                                    <tr>
                                        <td>
                                            <i class="fas fa-{{ $activity->type === 'sms' ? 'sms' : 'mobile-alt' }} me-2"></i>
                                            {{ strtoupper($activity->type) }}
                                        </td>
                                        <td>{{ $activity->recipient }}</td>
                                        <td>{{ $activity->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $activity->status === 'delivered' ? 'success' : 'warning' }}">
                                                {{ ucfirst($activity->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No recent activities</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('sms.compose') }}" class="btn btn-success">
                                <i class="fas fa-sms me-2"></i>Send SMS
                            </a>
                          
                            <a href="{{ url('contacts.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Add Contact
                            </a>
                            <a href="{{ url('credits.purchase') }}" class="btn btn-outline-success">
                                <i class="fas fa-shopping-cart me-2"></i>Purchase Credits
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection