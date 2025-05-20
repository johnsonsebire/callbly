<div class="card-title">@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h5 class="mb-0">USSD Analytics</h5>
                    </div><div class="card-title"></div><div class="card-title"></div>
                    </div><div class="card-title"></div><div class="card-title"></div>
                <div class="card-body">
                    @if($analyticsData->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">No analytics data available yet. Create a USSD service to start tracking usage.</p>
                            <a href="{{ route('ussd.services') }}" class="btn btn-primary">View USSD Services</a>
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @else
                        <div class="row g-4">
                            @foreach($analyticsData as $data)
                                <div class="col-md-6 col-xl-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $data['name'] }}</h6>
                                            <div class="d-flex flex-column gap-3 mt-4">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">Total Sessions</span>
                                                    <span class="fw-bold">{{ number_format($data['total_sessions']) }}</span>
                                                </div><div class="card-title"></div><div class="card-title"></div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">Active Users</span>
                                                    <span class="fw-bold">{{ number_format($data['active_users']) }}</span>
                                                </div><div class="card-title"></div><div class="card-title"></div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">Avg. Session Duration</span>
                                                    <span class="fw-bold">{{ $data['average_session_duration'] }}s</span>
                                                </div><div class="card-title"></div><div class="card-title"></div>
                                            </div><div class="card-title"></div><div class="card-title"></div>
                                        </div><div class="card-title"></div><div class="card-title"></div>
                                    </div><div class="card-title"></div><div class="card-title"></div>
                                </div><div class="card-title"></div><div class="card-title"></div>
                            @endforeach
                        </div><div class="card-title"></div><div class="card-title"></div>
                    @endif
                </div><div class="card-title"></div><div class="card-title"></div>
            </div><div class="card-title"></div><div class="card-title"></div>
        </div><div class="card-title"></div><div class="card-title"></div>
    </div><div class="card-title"></div><div class="card-title"></div>
</div><div class="card-title"></div></div>
@endsection