<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Reminder</title>
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
        .reminder-badge {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            color: #856404;
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
        .btn-secondary {
            background-color: #6c757d;
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
        .time-highlight {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
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
            <h1>Meeting Reminder</h1>
        </div>

        <div class="reminder-badge">
            <strong>⏰ Reminder: Your meeting is in {{ $reminderMinutes }} minutes!</strong>
        </div>

        @if($isHost)
            <p>Hello,</p>
            <p>This is a reminder that you have an upcoming meeting with <strong>{{ $booking->booker_name }}</strong>.</p>
        @else
            <p>Hi {{ $booking->booker_name }},</p>
            <p>This is a friendly reminder about your upcoming meeting.</p>
        @endif

        <div class="event-details">
            <h3>Meeting Details</h3>
            <div class="detail-row">
                <div class="detail-label">Event:</div>
                <div class="detail-value">{{ $eventType->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Time:</div>
                <div class="detail-value time-highlight">
                    {{ $booking->scheduled_at->setTimezone($booking->timezone)->format('g:i A') }}
                    ({{ $booking->timezone }})
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div class="detail-value">{{ $booking->scheduled_at->setTimezone($booking->timezone)->format('l, F j, Y') }}</div>
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
                @if($booking->booker_phone)
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">{{ $booking->booker_phone }}</div>
                    </div>
                @endif
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
        </div>

        @if($booking->google_meet_link)
            <div class="action-buttons">
                <a href="{{ $booking->google_meet_link }}" class="btn btn-primary">Join Google Meet</a>
            </div>
        @endif

        @if($eventType->description)
            <div class="event-details">
                <h3>About this meeting</h3>
                <p>{{ $eventType->description }}</p>
            </div>
        @endif

        @if($booking->custom_responses && count($booking->custom_responses) > 0)
            <div class="event-details">
                <h3>Additional Information</h3>
                @foreach($booking->custom_responses as $question => $answer)
                    <div class="detail-row">
                        <div class="detail-label">{{ $question }}:</div>
                        <div class="detail-value">{{ $answer }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!$isHost)
            <div class="action-buttons">
                <a href="{{ route('public.booking.manage', $booking->booking_reference) }}" class="btn btn-secondary">
                    Manage Booking
                </a>
            </div>
            
            <p style="text-align: center; color: #6c757d; margin-top: 20px;">
                Need to reschedule or cancel? 
                <a href="{{ route('public.booking.manage', $booking->booking_reference) }}">Click here</a>
            </p>
        @endif

        <div class="footer">
            <p>This meeting was scheduled using Callbly Meeting Scheduling Service.</p>
            @if(!$isHost)
                <p>
                    <a href="{{ route('public.booking.manage', $booking->booking_reference) }}">Manage this booking</a> |
                    <a href="mailto:support@callbly.com">Contact Support</a>
                </p>
            @endif
        </div>
    </div>
</body>
</html>