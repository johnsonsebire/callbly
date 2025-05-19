@extends('layouts.app')

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
                    <h5 class="mb-0">Your Sender Names</h5>
                </div>
                <div class="card-body">
                    @if($senderNames->isEmpty())
                        <p class="text-center my-4">You haven't registered any sender names yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
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
                                            <td>{{ $senderName->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $senderName->status == 'approved' ? 'success' : ($senderName->status == 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($senderName->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $senderName->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($senderName->status == 'pending')
                                                    Awaiting approval
                                                @elseif($senderName->status == 'rejected')
                                                    {{ $senderName->rejection_reason ?? 'Rejected by provider' }}
                                                @else
                                                    Active and ready to use
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
                <div class="card-header">
                    <h5 class="mb-0">Register New Sender ID</h5>
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

                    <form method="POST" action="{{ route('sms.sender-names.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name">Sender Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="11" required>
                            <small class="text-muted">
                                Sender name should be 3-11 alphanumeric characters (A-Z, 0-9)
                            </small>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Register Sender ID</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Sender ID Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="pl-3">
                        <li>Sender ID must be 3-11 characters long</li>
                        <li>Only alphanumeric characters are allowed (A-Z, 0-9)</li>
                        <li>No spaces or special characters</li>
                        <li>Approval typically takes 1-3 business days</li>
                        <li>Brand names should match your registered business name</li>
                        <li>Generic terms like "INFO" or "SMS" may not be approved</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection