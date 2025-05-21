@extends('emails.layouts.master')

@section('title', 'Welcome to ' . config('app.name'))

@section('content')
    <h1 class="email-heading">Welcome to {{ config('app.name') }}!</h1>
    
    <p class="email-text">Hello {{ $user->name }},</p>
    
    <p class="email-text">Thank you for signing up with {{ config('app.name') }}. We're excited to have you on board!</p>
    
    <p class="email-text">Your account has been created successfully. Here's what you can do next:</p>
    
    <div class="email-section">
        <div class="email-card">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Getting Started</h3>
            <ul style="padding-left: 20px; margin-bottom: 0;">
                <li class="email-text mb-2">Complete your profile information</li>
                <li class="email-text mb-2">Set up your sender names</li>
                <li class="email-text mb-2">Import your contacts</li>
                <li class="email-text mb-0">Send your first SMS campaign</li>
            </ul>
        </div>
    </div>
    
    <div class="email-section text-center">
        <a href="{{ $dashboardUrl }}" class="email-btn">Go to Your Dashboard</a>
    </div>
    
    <div class="email-section">
        <div class="email-card">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Your Account Information</h3>
            <p class="email-text mb-2"><strong>Name:</strong> {{ $user->name }}</p>
            <p class="email-text mb-2"><strong>Email:</strong> {{ $user->email }}</p>
            @if($user->company_name)
            <p class="email-text mb-0"><strong>Company:</strong> {{ $user->company_name }}</p>
            @endif
        </div>
    </div>
    
    <p class="email-text">If you have any questions or need assistance, feel free to contact our support team at <a href="mailto:support@callbly.com">support@callbly.com</a>.</p>
    
    <p class="email-text mb-0">Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection