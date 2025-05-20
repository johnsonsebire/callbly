<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px me-3">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-outline ki-call fs-2x text-primary"></i>
                            </span>
                        </div>
                        <div>
                            <h3 class="fs-2 text-gray-800 mb-0">{{ $activeNumbers }}</h3>
                            <span class="text-gray-500 fw-semibold">Active Numbers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px me-3">
                            <span class="symbol-label bg-light-warning">
                                <i class="ki-outline ki-timer fs-2x text-warning"></i>
                            </span>
                        </div>
                        <div>
                            <h3 class="fs-2 text-gray-800 mb-0">{{ $expiringNumbers }}</h3>
                            <span class="text-gray-500 fw-semibold">Expiring Soon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">Virtual Numbers</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($activeNumbers == 0)
                        <div class="text-center py-5">
                            <img src="{{ asset('assets/media/svg/illustrations/calling.svg') }}" class="mw-400px mb-5" alt="">
                            <h3 class="fs-2 mb-3">Get Started with Virtual Numbers</h3>
                            <p class="text-gray-500 mb-4">Browse our selection of local and toll-free numbers<br>to establish your presence in multiple regions.</p>
                            <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary">Browse Numbers</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($activeNumbers > 0)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h5 class="mb-0">Your Numbers</h5>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('virtual-numbers.browse') }}" class="btn btn-primary">Get More Numbers</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                <tr>
                                    <th>Number</th>
                                    <th>Type</th>
                                    <th>Monthly Fee</th>
                                    <th>Status</th>
                                    <th>Expires</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-6 fw-semibold text-gray-600">
                                @foreach($virtualNumbers as $number)
                                <tr>
                                    <td>{{ $number->number }}</td>
                                    <td>{{ ucfirst($number->type) }}</td>
                                    <td>{{ $number->monthly_fee }}</td>
                                    <td>
                                        <span class="badge badge-light-{{ $number->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($number->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $number->expires_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection