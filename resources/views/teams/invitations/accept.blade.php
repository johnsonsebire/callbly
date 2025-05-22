@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Accept Team Invitation</div>

                <div class="card-body">
                    <h4>Join {{ $invitation->team->name }}</h4>
                    <p>{{ $invitation->team->owner->name }} has invited you to join their team with the role of {{ ucfirst($invitation->role) }}.</p>

                    <div class="d-flex justify-content-between mt-4">
                        <form method="POST" action="{{ route('team-invitations.accept', $invitation->token) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Accept Invitation</button>
                        </form>

                        <form method="POST" action="{{ route('team-invitations.decline', $invitation->token) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">Decline</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection