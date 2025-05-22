<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Support Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px 5px 0 0;
            border-bottom: 3px solid #009ef7;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .priority {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
            margin-bottom: 10px;
        }
        .priority-low {
            background-color: #50cd89;
        }
        .priority-medium {
            background-color: #ffc700;
        }
        .priority-high {
            background-color: #f1416c;
        }
        .info-label {
            font-weight: bold;
            color: #5e6278;
        }
        .info-value {
            margin-bottom: 15px;
        }
        .message-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #a1a5b7;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0; color: #181c32; font-size: 20px;">New Support Request</h2>
    </div>
    
    <div class="content">
        <div class="priority priority-{{ strtolower($supportData['priority']) }}">
            {{ $supportData['priority'] }} Priority
        </div>
        
        <div>
            <p class="info-label">From:</p>
            <p class="info-value">{{ $supportData['name'] }} ({{ $supportData['email'] }})</p>
            
            <p class="info-label">Subject:</p>
            <p class="info-value">{{ $supportData['subject'] }}</p>
            
            <p class="info-label">Submitted At:</p>
            <p class="info-value">{{ $supportData['submitted_at']->format('F j, Y, g:i a') }}</p>
            
            <p class="info-label">Message:</p>
            <div class="message-box">{{ $supportData['message'] }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>This email was sent from the Callbly support system.</p>
        <p>© {{ date('Y') }} Callbly. All rights reserved.</p>
    </div>
</body>
</html>