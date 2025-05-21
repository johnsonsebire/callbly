<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #3F4254;
            background-color: #f5f8fa;
            margin: 0;
            padding: 0;
        }
        
        .email-wrapper {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        /* Header Styles */
        .email-header {
            background-color: #009ef7;
            padding: 24px;
            text-align: center;
        }
        
        .email-header-logo {
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
            text-decoration: none;
        }
        
        /* Content Styles */
        .email-content {
            padding: 30px 24px;
        }
        
        .email-heading {
            font-size: 22px;
            font-weight: 600;
            color: #181C32;
            margin-top: 0;
            margin-bottom: 16px;
        }
        
        .email-text {
            font-size: 15px;
            line-height: 1.6;
            color: #3F4254;
            margin-top: 0;
            margin-bottom: 16px;
        }
        
        /* Button Styles */
        .email-btn {
            display: inline-block;
            background-color: #009ef7;
            color: #ffffff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
            margin-top: 8px;
            margin-bottom: 8px;
        }
        
        .email-btn-secondary {
            background-color: #E4E6EF;
            color: #3F4254;
        }
        
        /* Section & Card Styles */
        .email-section {
            margin-bottom: 24px;
        }
        
        .email-card {
            border: 1px solid #E4E6EF;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 16px;
        }
        
        /* Footer Styles */
        .email-footer {
            background-color: #F9FAFB;
            padding: 20px 24px;
            text-align: center;
            color: #7E8299;
            font-size: 13px;
        }
        
        .email-footer a {
            color: #009ef7;
            text-decoration: none;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .text-muted {
            color: #7E8299;
        }
        
        .mb-0 {
            margin-bottom: 0;
        }
        
        .mb-2 {
            margin-bottom: 8px;
        }
        
        .mb-4 {
            margin-bottom: 16px;
        }
        
        /* Mobile Responsiveness */
        @media only screen and (max-width: 620px) {
            .email-wrapper {
                padding: 12px;
            }
            
            .email-content, .email-header, .email-footer {
                padding: 24px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <a href="{{ config('app.url') }}" class="email-header-logo">
                    {{ config('app.name') }}
                </a>
            </div>
            
            <!-- Content -->
            <div class="email-content">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <div class="email-footer">
                <p class="mb-2">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
                <p class="mb-0">
                    <a href="{{ config('app.url') }}/terms">Terms of Service</a> &middot; 
                    <a href="{{ config('app.url') }}/privacy">Privacy Policy</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>