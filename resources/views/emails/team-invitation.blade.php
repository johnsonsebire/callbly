@extends('emails.layouts.master')

@section('title', 'Team Invitation')

@section('content')
    <h1 class="email-heading">You've Been Invited to Join a Team</h1>
    
    <p class="email-text">
        Hi there,
    </p>

    <p class="email-text">
        {{ $inviterName }} has invited you to join their team "{{ $teamName }}" on {{ config('app.name') }}.
    </p>

    <div class="email-section">
        <div class="email-card">
            <p class="email-text mb-2">
                <strong>Team:</strong> {{ $teamName }}
            </p>
            <p class="email-text mb-2">
                <strong>Invited by:</strong> {{ $inviterName }}
            </p>
            <p class="email-text mb-0">
                <strong>Expires:</strong> {{ $expiresAt }}
            </p>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ $url }}" class="email-btn">
            Accept Invitation
        </a>
    </div>

    <p class="email-text text-muted">
        If you don't have an account yet, you'll be able to create one after clicking the button above.
        This invitation will expire on {{ $expiresAt }}.
    </p>

    <p class="email-text text-muted">
        If you did not expect this invitation, you can safely ignore this email.
    </p>
@endsection