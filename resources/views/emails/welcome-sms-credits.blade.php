@extends('emails.layouts.app')

@section('content')
<div style="padding: 40px 0;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #1a202c; font-size: 32px; font-weight: bold; margin: 0;">
            Welcome to Callbly! 🎉
        </h1>
    </div>
    
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; text-align: center; margin-bottom: 30px;">
        <h2 style="margin: 0 0 15px 0; font-size: 24px;">Free SMS Credits</h2>
        <div style="font-size: 48px; font-weight: bold; margin: 10px 0;">{{ $creditsAmount }}</div>
        <p style="margin: 0; font-size: 16px; opacity: 0.9;">SMS Credits added to your account</p>
    </div>
    
    <div style="margin-bottom: 30px;">
        <p style="font-size: 16px; line-height: 1.6; color: #4a5568; margin-bottom: 15px;">
            Hi {{ $user->name }},
        </p>
        <p style="font-size: 16px; line-height: 1.6; color: #4a5568; margin-bottom: 15px;">
            Welcome to Callbly! We're excited to have you on board. To help you get started with our SMS platform, we've added <strong>{{ $creditsAmount }} free SMS credits</strong> to your account.
        </p>
        <p style="font-size: 16px; line-height: 1.6; color: #4a5568; margin-bottom: 15px;">
            You can use these credits to:
        </p>
        <ul style="font-size: 16px; line-height: 1.6; color: #4a5568; margin-bottom: 20px; padding-left: 20px;">
            <li style="margin-bottom: 8px;">Send SMS campaigns to your contacts</li>
            <li style="margin-bottom: 8px;">Test our SMS delivery and features</li>
            <li style="margin-bottom: 8px;">Experience the quality of our SMS service</li>
        </ul>
    </div>
    
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ route('sms.compose') }}" style="background: #4299e1; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px;">
            Start Sending SMS
        </a>
    </div>
    
    <div style="background: #f7fafc; padding: 25px; border-radius: 8px; margin: 30px 0;">
        <h3 style="color: #2d3748; font-size: 18px; margin: 0 0 15px 0;">Getting Started Tips:</h3>
        <ol style="color: #4a5568; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 20px;">
            <li style="margin-bottom: 8px;">Register your sender name for professional messaging</li>
            <li style="margin-bottom: 8px;">Import your contacts or create contact groups</li>
            <li style="margin-bottom: 8px;">Create SMS templates for faster messaging</li>
            <li style="margin-bottom: 8px;">Schedule campaigns for optimal delivery times</li>
        </ol>
    </div>
    
    <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e2e8f0;">
        <p style="font-size: 14px; color: #718096; margin-bottom: 15px;">
            Need help getting started? Check out our resources:
        </p>
        <div style="margin-bottom: 15px;">
            <a href="{{ route('sms.dashboard') }}" style="color: #4299e1; text-decoration: none; font-size: 14px; margin-right: 20px;">SMS Dashboard</a>
            <a href="{{ route('contacts.index') }}" style="color: #4299e1; text-decoration: none; font-size: 14px; margin-right: 20px;">Manage Contacts</a>
            <a href="{{ route('sms.sender-names') }}" style="color: #4299e1; text-decoration: none; font-size: 14px;">Sender Names</a>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 40px;">
        <p style="font-size: 16px; color: #4a5568; margin-bottom: 10px;">
            Happy messaging!
        </p>
        <p style="font-size: 16px; color: #4a5568; font-weight: bold;">
            The Callbly Team
        </p>
    </div>
</div>
@endsection
