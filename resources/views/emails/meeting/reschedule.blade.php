<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Rescheduled</title>
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
        .reschedule-badge {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            margin: 20px 0;
            color: #0c5460;
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
        .time-comparison {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 15px;
            align-items: center;
            margin: 20px 0;
        }
        .old-time {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            color: #721c24;
        }
        .new-time {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            color: #155724;
        }
        .arrow {
            font-size: 24px;
            color: #007bff;
            text-align: center;
        }
        .time-highlight {
            font-size: 18px;
            font-weight: bold;
        }
        @media (max-width: 600px) {
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                min-width: auto;
                margin-bottom: 5px;
            }
            .time-comparison {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            .arrow {
                transform: rotate(90deg);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ $companyProfile->company_name ?? 'Callbly' }}</div>
            <h1>Meeting Rescheduled</h1>
        </div>

        <div class="reschedule-badge">
            <strong>📅 Your meeting has been rescheduled</strong>
        </div>

        @if($isHost)
            <p>Hello,</p>
            <p>You have rescheduled your meeting with <strong>{{ $booking->booker_name }}</strong>.</p>
        @else
            <p>Hi {{ $booking->booker_name }},</p>
            <p>We're writing to inform you that your scheduled meeting has been rescheduled to a new time.</p>
        @endif

        @if($reason)
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 15px; margin: 20px 0;">
                <strong>Reason for Reschedule:</strong><br>
                {{ $reason }}
            </div>
        @endif

        @if($oldScheduledAt)
            <div class="time-comparison">
                <div class="old-time">
                    <strong>Previous Time</strong><br>
                    <div class="time-highlight">
                        {{ $oldScheduledAt->setTimezone($booking->timezone)->format('l, F j, Y') }}<br>
                        {{ $oldScheduledAt->setTimezone($booking->timezone)->format('g:i A') }}
                    </div>
                </div>
                <div class="arrow">→</div>
                <div class="new-time">
                    <strong>New Time</strong><br>
                    <div class="time-highlight">
                        {{ $booking->scheduled_at->setTimezone($booking->timezone)->format('l, F j, Y') }}<br>
                        {{ $booking->scheduled_at->setTimezone($booking->timezone)->format('g:i A') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="event-details">
            <h3>Updated Meeting Details</h3>
            <div class="detail-row">
                <div class="detail-label">Event:</div>
                <div class="detail-value">{{ $eventType->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">New Date & Time:</div>
                <div class="detail-value">
                    {{ $booking->scheduled_at->setTimezone($booking->timezone)->format('l, F j, Y') }}<br>
                    <strong>{{ $booking->scheduled_at->setTimezone($booking->timezone)->format('g:i A') }} - 
                    {{ $booking->scheduled_end_at->setTimezone($booking->timezone)->format('g:i A') }}</strong>
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
                Need to make changes? 
                <a href="{{ route('public.booking.manage', $booking->booking_reference) }}">Manage your booking</a>
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