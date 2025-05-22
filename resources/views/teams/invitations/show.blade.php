@extends('layouts.auth')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Team Invitation</h3>
    </div>
    <div class="card-body">
        <div class="text-center mb-5">
            <div class="symbol symbol-60px symbol-circle mb-3">
                <div class="symbol-label bg-light-primary">
                    <span class="fs-1 text-primary">{{ strtoupper(substr($invitation->team->name, 0, 1)) }}</span>
                </div>
            </div>
            <h2 class="fs-1 fw-bold mb-1">{{ $invitation->team->name }}</h2>
            <p class="text-muted mb-0">{{ $invitation->team->description }}</p>
        </div>

        <div class="mb-5">
            <p>
                <strong>{{ $invitation->team->owner->name }}</strong> has invited you to join their team as a <strong>{{ ucfirst($invitation->role) }}</strong>.
                This invitation will expire on {{ $invitation->expires_at->format('F j, Y') }}.
            </p>
        </div>
        
        <div class="separator separator-dashed my-8"></div>

        <div class="mb-5">
            <h4 class="fs-4 fw-bold mb-3">What happens when you accept?</h4>
            <ul class="list-group list-group-flush border-0">
                <li class="list-group-item d-flex align-items-center py-3 px-0">
                    <div class="bullet bg-primary me-3"></div>
                    <div>You'll be added to the team with {{ ucfirst($invitation->role) }} permissions</div>
                </li>
                <li class="list-group-item d-flex align-items-center py-3 px-0">
                    <div class="bullet bg-primary me-3"></div>
                    <div>You'll be able to collaborate with other team members</div>
                </li>
                <li class="list-group-item d-flex align-items-center py-3 px-0">
                    <div class="bullet bg-primary me-3"></div>
                    <div>You'll have access to shared team resources based on team settings</div>
                </li>
            </ul>
        </div>

        <div class="d-flex flex-stack">
            @auth
                <form method="POST" action="{{ route('team-invitations.decline', $invitation->token) }}">
                    @csrf
                    <button type="submit" class="btn btn-light-danger">Decline Invitation</button>
                </form>
                
                <form method="POST" action="{{ route('team-invitations.accept', $invitation->token) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Accept Invitation</button>
                </form>
            @else
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 w-100">
                    <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">You need to log in first</h4>
                            <div class="fs-6 text-gray-700">
                                <p class="mb-3">You need to log in to your account before you can accept this invitation.</p>
                                <div class="d-flex flex-column flex-sm-row">
                                    <a href="{{ route('login') }}?redirect_to={{ urlencode(route('team-invitations.show', $invitation->token)) }}" class="btn btn-primary me-2 mb-2">Log In</a>
                                    <a href="{{ route('register') }}?redirect_to={{ urlencode(route('team-invitations.show', $invitation->token)) }}" class="btn btn-light-primary mb-2">Create Account</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection