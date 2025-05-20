@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h5 class="mb-0">Approve Sender Names</h5>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($pending->isEmpty())
                <p class="text-center">No pending sender names to approve.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Sender ID</th>
                                <th>Requested At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending as $item)
                                <tr>
                                    <td>{{ $item->user->name }} ({{ $item->user->email }})</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->created_at->format('M d, Y H:i') }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.sender-names.update', $item->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button name="status" value="approved" class="btn btn-sm btn-success me-1">Approve</button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="collapse" data-bs-target="#reject-{{ $item->id }}">Reject</button>
                                        <div class="collapse mt-2" id="reject-{{ $item->id }}">
                                            <form method="POST" action="{{ route('admin.sender-names.update', $item->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-1">
                                                    <textarea name="rejection_reason" class="form-control form-control-sm" placeholder="Rejection reason" required></textarea>
                                                </div>
                                                <button name="status" value="rejected" class="btn btn-sm btn-danger">Submit</button>
                                            </form>
                                        </div>
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
@endsection