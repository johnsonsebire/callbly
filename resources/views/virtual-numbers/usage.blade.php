@extends('layouts.master')

@section('title', 'Virtual Number Usage - ' . $number->number)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Usage Statistics for {{ $number->number }}</h4>
                        <a href="{{ route('virtual-numbers.my-numbers') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to My Numbers
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <input type="text" class="form-control" id="dateRange" name="dateRange">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Total Calls</h6>
                                    <h3 class="mb-0" id="totalCalls">{{ $usageStats['total_calls'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Total Minutes</h6>
                                    <h3 class="mb-0" id="totalMinutes">{{ number_format(($usageStats['total_duration'] ?? 0) / 60, 1) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">SMS Received</h6>
                                    <h3 class="mb-0" id="smsReceived">{{ $usageStats['sms_received'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">SMS Sent</h6>
                                    <h3 class="mb-0" id="smsSent">{{ $usageStats['sms_sent'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Usage History</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table" id="usageTable">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th>Duration</th>
                                                    <th>Status</th>
                                                    <th>Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($usageStats['logs'] ?? [] as $log)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($log['timestamp'])->format('M d, Y H:i') }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $log['type'] === 'call' ? 'primary' : 'info' }}">
                                                                {{ ucfirst($log['type']) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $log['from'] }}</td>
                                                        <td>{{ $log['to'] }}</td>
                                                        <td>{{ $log['type'] === 'call' ? gmdate('H:i:s', $log['duration']) : 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $log['status'] === 'completed' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($log['status']) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ auth()->user()->currency->symbol }}{{ number_format($log['cost'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range picker
    $('#dateRange').daterangepicker({
        startDate: '{{ $startDate->format("Y-m-d") }}',
        endDate: '{{ $endDate->format("Y-m-d") }}',
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end) {
        // Fetch usage data for the selected date range
        fetchUsageData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
    });
});

function fetchUsageData(startDate, endDate) {
    $.ajax({
        url: '/api/virtual-numbers/{{ $number->id }}/usage',
        data: {
            start_date: startDate,
            end_date: endDate
        },
        success: function(response) {
            if (response.success) {
                updateUsageStats(response.data);
            } else {
                toastr.error('Failed to fetch usage data');
            }
        },
        error: function() {
            toastr.error('An error occurred while fetching usage data');
        }
    });
}

function updateUsageStats(data) {
    $('#totalCalls').text(data.total_calls || 0);
    $('#totalMinutes').text(((data.total_duration || 0) / 60).toFixed(1));
    $('#smsReceived').text(data.sms_received || 0);
    $('#smsSent').text(data.sms_sent || 0);
    
    // Update table
    const tbody = $('#usageTable tbody');
    tbody.empty();
    
    (data.logs || []).forEach(function(log) {
        tbody.append(`
            <tr>
                <td>${moment(log.timestamp).format('MMM DD, YYYY HH:mm')}</td>
                <td>
                    <span class="badge bg-${log.type === 'call' ? 'primary' : 'info'}">
                        ${log.type.charAt(0).toUpperCase() + log.type.slice(1)}
                    </span>
                </td>
                <td>${log.from}</td>
                <td>${log.to}</td>
                <td>${log.type === 'call' ? new Date(log.duration * 1000).toISOString().substr(11, 8) : 'N/A'}</td>
                <td>
                    <span class="badge bg-${log.status === 'completed' ? 'success' : 'warning'}">
                        ${log.status.charAt(0).toUpperCase() + log.status.slice(1)}
                    </span>
                </td>
                <td>${'{{ auth()->user()->currency->symbol }}'}${parseFloat(log.cost).toFixed(2)}</td>
            </tr>
        `);
    });
}
</script>
@endpush
@endsection