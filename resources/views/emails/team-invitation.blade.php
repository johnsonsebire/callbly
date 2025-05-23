@extends('emails.layouts.master')

@section('title', 'Team Invitation')

@section('content')
<div class="email-section">
    <h1 class="email-heading">You've Been Invited to Join a Team</h1>
    
    <p class="email-text">
        {{ $invitation->team->owner->name }} has invited you to join their team "{{ $invitation->team->name }}" with the role of {{ ucfirst($invitation->role) }}.
    </p>
    
    <div class="email-card mb-4">
        <p class="mb-2 fw-bold">Quick Instructions:</p>
        <ul style="margin-bottom: 20px;">
            <li>If you don't have an account, you'll need to create one with this email ({{ $invitation->email }})</li>
            <li>If you already have an account, just click the button below to join</li>
            <li>This invitation will expire in 7 days</li>
        </ul>
    </div>

    <div class="text-center">
        <a href="{{ route('team-invitations.show', $invitation->token) }}" class="email-btn">
            Accept Invitation
        </a>
    </div>

    <div class="email-card mt-4" style="background-color: #f8f9fa;">
        <p class="mb-2">If the button above doesn't work, copy and paste this link into your browser:</p>
        <div style="background: #fff; padding: 10px; border-radius: 4px; word-break: break-all;">
            {{ route('team-invitations.show', $invitation->token) }}
        </div>
    </div>
</div>
@endsection