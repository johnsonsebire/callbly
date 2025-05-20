@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Sender Names</h2>
            <p class="text-muted">Manage your SMS sender IDs</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                    <h5 class="mb-0">Your Sender Names</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mb-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($senderNames->isEmpty())
                        <div class="text-center my-5">
                            <i class="fas fa-id-badge fa-3x text-muted mb-3"></i>
                            <p class="lead">You haven't registered any sender names yet.</p>
                            <p class="text-muted">A sender name allows recipients to identify who sent the message.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sender ID</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($senderNames as $senderName)
                                        <tr>
                                            <td><strong>{{ $senderName->name }}</strong></td>
                                            <td>
                                                @if($senderName->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($senderName->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $senderName->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($senderName->status == 'pending')
                                                    <span class="text-warning">
                                                        <i class="fas fa-clock me-1"></i> Awaiting approval
                                                    </span>
                                                @elseif($senderName->status == 'rejected')
                                                    <span class="text-danger">
                                                        <i class="fas fa-times-circle me-1"></i> {{ $senderName->rejection_reason ?? 'Rejected by provider' }}
                                                    </span>
                                                @else
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i> Active and ready to use
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="card-title">
                        <h5 class="mb-0 text-white">Register New Sender ID</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('sms.sender-names.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Sender Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" maxlength="11" required>
                            <small class="text-muted">
                                Sender name should be 3-11 alphanumeric characters (A-Z, 0-9)
                            </small>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Register Sender ID
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-light">
                    <div class="card-title">
                        <h5 class="mb-0">Sender ID Registration Guidelines</h5>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success me-2"></i> 3-11 characters long</li>
                        <li><i class="fas fa-check-circle text-success me-2"></i> Only alphanumeric characters (A-Z, 0-9)</li>
                        <li><i class="fas fa-times-circle text-danger me-2"></i> No spaces or special characters</li>
                        <li><i class="fas fa-info-circle text-info me-2"></i> Approval takes 1-3 business days</li>
                        <li><i class="fas fa-trademark text-primary me-2"></i> Brand names should match your business</li>
                        <li><i class="fas fa-exclamation-triangle text-warning me-2"></i> Generic terms like "INFO" may be rejected</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection