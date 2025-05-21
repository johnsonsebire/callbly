@extends('emails.layouts.master')

@section('title', 'Reset Your Password')

@section('content')
    <h1 class="email-heading">Reset Your Password</h1>
    
    <p class="email-text">Hello,</p>
    
    <p class="email-text">We received a request to reset your password. If you didn't make this request, you can safely ignore this email.</p>
    
    <p class="email-text">To reset your password, click the button below. This link will expire in 60 minutes.</p>
    
    <div class="email-section text-center">
        <a href="{{ $url }}" class="email-btn">Reset Password</a>
    </div>
    
    <p class="email-text">If the button above doesn't work, copy and paste the URL below into your web browser:</p>
    
    <div class="email-card" style="word-break: break-all; font-size: 13px;">
        <p style="margin: 0;">{{ $url }}</p>
    </div>
    
    <div class="email-section">
        <div class="email-card">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Security Notice</h3>
            <p class="email-text mb-0">If you didn't request a password reset, please contact our support team immediately at <a href="mailto:support@callbly.com" style="color: #009ef7; text-decoration: none;">support@callbly.com</a>.</p>
        </div>
    </div>
    
    <p class="email-text mb-0">Best regards,<br>The Callbly Team</p>
@endsection