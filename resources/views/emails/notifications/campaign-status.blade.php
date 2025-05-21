@extends('emails.layouts.master')

@section('title', 'SMS Campaign Status Update')

@section('content')
    <h1 class="email-heading">SMS Campaign Status Update</h1>
    
    <p class="email-text">Hello {{ $user->name }},</p>
    
    <p class="email-text">
        Your SMS campaign "{{ $campaign->name }}" has {{ $campaign->status === 'completed' ? 'been completed' : 'a status update' }}.
    </p>
    
    <div class="email-section">
        <div class="email-card" style="border-left: 4px solid {{ $campaign->status === 'completed' ? '#50CD89' : ($campaign->status === 'failed' ? '#F1416C' : '#FFC700') }};">
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="padding: 4px 0; color: #7E8299; width: 140px;">Campaign Name:</td>
                        <td style="padding: 4px 0; font-weight: 500;">{{ $campaign->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #7E8299;">Status:</td>
                        <td style="padding: 4px 0;">
                            <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; color: #FFFFFF; background-color: {{ $campaign->status === 'completed' ? '#50CD89' : ($campaign->status === 'failed' ? '#F1416C' : '#FFC700') }};">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #7E8299;">Sender ID:</td>
                        <td style="padding: 4px 0; font-weight: 500;">{{ $campaign->sender_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #7E8299;">Recipients:</td>
                        <td style="padding: 4px 0; font-weight: 500;">{{ number_format($campaign->recipients_count) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #7E8299;">Started At:</td>
                        <td style="padding: 4px 0; font-weight: 500;">{{ $campaign->started_at ? $campaign->started_at->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #7E8299;">Completed At:</td>
                        <td style="padding: 4px 0; font-weight: 500;">{{ $campaign->completed_at ? $campaign->completed_at->format('M d, Y H:i') : 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="email-section">
        <h2 style="font-size: 18px; font-weight: 600; color: #181C32; margin-bottom: 12px;">Delivery Statistics</h2>
        
        <div style="display: flex; flex-wrap: wrap; margin-left: -8px; margin-right: -8px;">
            <div style="flex: 1; min-width: 140px; padding: 8px;">
                <div style="background-color: #F8F9FA; border-radius: 6px; padding: 16px; text-align: center;">
                    <p style="margin: 0 0 4px; font-size: 13px; color: #7E8299;">Delivered</p>
                    <h3 style="margin: 0; color: #50CD89; font-size: 22px; font-weight: 600;">{{ number_format($campaign->delivered_count) }}</h3>
                </div>
            </div>
            <div style="flex: 1; min-width: 140px; padding: 8px;">
                <div style="background-color: #F8F9FA; border-radius: 6px; padding: 16px; text-align: center;">
                    <p style="margin: 0 0 4px; font-size: 13px; color: #7E8299;">Failed</p>
                    <h3 style="margin: 0; color: #F1416C; font-size: 22px; font-weight: 600;">{{ number_format($campaign->failed_count) }}</h3>
                </div>
            </div>
            <div style="flex: 1; min-width: 140px; padding: 8px;">
                <div style="background-color: #F8F9FA; border-radius: 6px; padding: 16px; text-align: center;">
                    <p style="margin: 0 0 4px; font-size: 13px; color: #7E8299;">Pending</p>
                    <h3 style="margin: 0; color: #FFC700; font-size: 22px; font-weight: 600;">{{ number_format($campaign->pending_count) }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="email-section text-center">
        <a href="{{ route('sms.campaign-details', $campaign->id) }}" class="email-btn">View Campaign Details</a>
    </div>
    
    @if($campaign->status === 'failed' && $campaign->failure_reason)
    <div class="email-section">
        <div class="email-card" style="background-color: #FFF5F8; border-color: #F1416C;">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Failure Information</h3>
            <p class="email-text mb-0">{{ $campaign->failure_reason }}</p>
        </div>
    </div>
    @endif
    
    <p class="email-text mb-0">Best regards,<br>The Callbly Team</p>
@endsection