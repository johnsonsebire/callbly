<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Cancelled</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }
        .cancellation-badge {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            color: #721c24;
        }
        .event-details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            min-width: 120px;
            color: #6c757d;
        }
        .detail-value {
            color: #333;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 14px;
        }
        .company-info {
            background: #e3f2fd;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        @media (max-width: 600px) {
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ $companyProfile->company_name ?? 'Callbly' }}</div>
            <h1>Meeting Cancelled</h1>
        </div>

        <div class="cancellation-badge">
            <strong>❌ This meeting has been cancelled</strong>
        </div>

        @if($isHost)
            <p>Hello,</p>
            <p>You have cancelled your meeting with <strong>{{ $booking->booker_name }}</strong>.</p>
        @else
            <p>Hi {{ $booking->booker_name }},</p>
            <p>We're writing to inform you that your scheduled meeting has been cancelled.</p>
        @endif

        @if($reason)
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <strong>Cancellation Reason:</strong><br>
                {{ $reason }}
            </div>
        @endif

        <div class="event-details">
            <h3>Cancelled Meeting Details</h3>
            <div class="detail-row">
                <div class="detail-label">Event:</div>
                <div class="detail-value">{{ $eventType->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Date & Time:</div>
                <div class="detail-value">
                    {{ $booking->scheduled_at->setTimezone($booking->timezone)->format('l, F j, Y') }}<br>
                    {{ $booking->scheduled_at->setTimezone($booking->timezone)->format('g:i A') }} - 
                    {{ $booking->scheduled_end_at->setTimezone($booking->timezone)->format('g:i A') }}
                    ({{ $booking->timezone }})
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Duration:</div>
                <div class="detail-value">{{ $eventType->duration_minutes }} minutes</div>
            </div>
            @if($isHost)
                <div class="detail-row">
                    <div class="detail-label">Attendee:</div>
                    <div class="detail-value">{{ $booking->booker_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Email:</div>
                    <div class="detail-value">{{ $booking->booker_email }}</div>
                </div>
            @else
                @if($companyProfile)
                    <div class="detail-row">
                        <div class="detail-label">Host:</div>
                        <div class="detail-value">{{ $companyProfile->contact_person ?? $booking->user->name }}</div>
                    </div>
                @endif
            @endif
            <div class="detail-row">
                <div class="detail-label">Booking ID:</div>
                <div class="detail-value">{{ $booking->booking_reference }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Cancelled On:</div>
                <div class="detail-value">{{ $booking->updated_at->format('l, F j, Y \a\t g:i A') }}</div>
            </div>
        </div>

        @if(!$isHost && $companyProfile)
            <div class="action-buttons">
                <a href="{{ route('public.company', $companyProfile->brand_name) }}" class="btn btn-primary">
                    Schedule Another Meeting
                </a>
            </div>
            
            <div class="company-info">
                <h4>About {{ $companyProfile->company_name }}</h4>
                @if($companyProfile->description)
                    <p>{{ $companyProfile->description }}</p>
                @endif
                @if($companyProfile->website)
                    <p><strong>Website:</strong> <a href="{{ $companyProfile->website }}">{{ $companyProfile->website }}</a></p>
                @endif
            </div>
        @endif

        <div class="footer">
            <p>This meeting was scheduled using Callbly Meeting Scheduling Service.</p>
            @if(!$isHost)
                <p>
                    <a href="mailto:support@callbly.com">Contact Support</a>
                </p>
            @endif
        </div>
    </div>
</body>
</html>