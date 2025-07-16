<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sender Name Whitelist Request</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            margin: 40px;
            padding: 20px;
        }
        
        .letterhead {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 20px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 11px;
            color: #666;
            line-height: 1.3;
        }
        
        .document-header {
            text-align: center;
            margin: 30px 0;
        }
        
        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .reference-number {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .date-section {
            text-align: right;
            margin: 20px 0;
            font-size: 11px;
        }
        
        .recipient-section {
            margin: 30px 0;
        }
        
        .recipient-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .subject-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        
        .subject-text {
            font-size: 14px;
            font-weight: bold;
            color: #0066cc;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .content-section {
            margin: 20px 0;
            text-align: justify;
            line-height: 1.6;
        }
        
        .content-section p {
            margin-bottom: 15px;
        }
        
        .sender-name-highlight {
            background-color: #e7f3ff;
            border: 2px solid #0066cc;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .sender-name-text {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
            letter-spacing: 2px;
        }
        
        .sample-message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }
        
        .sample-message-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-style: normal;
        }
        
        .user-details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .user-details-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        
        .user-details-table td:first-child {
            font-weight: bold;
            width: 30%;
            color: #0066cc;
        }
        
        .signature-section {
            margin-top: 40px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin: 30px 0 10px 0;
        }
        
        .signature-text {
            font-size: 11px;
            color: #666;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Letterhead -->
    <div class="letterhead">
        <div class="logo">Callbly</div>
        <div class="company-info">
            Communications Platform<br>
            Email: support@callbly.com | Web: www.callbly.com<br>
            Professional SMS & Communication Services
        </div>
    </div>

    <!-- Document Header -->
    <div class="document-header">
        <div class="document-title">Sender Name Whitelist Request</div>
        <div class="reference-number">Reference: {{ $request_reference }}</div>
    </div>

    <!-- Date -->
    <div class="date-section">
        <strong>Date:</strong> {{ $request_date }}
    </div>

    <!-- Recipient -->
    <div class="recipient-section">
        <div class="recipient-title">To: Network Provider / Telecom Operator</div>
        <div>SMS Gateway Department</div>
        <div>Sender ID Approval Team</div>
    </div>

    <!-- Subject -->
    <div class="subject-section">
        <div class="subject-text">Request for Sender Name Whitelist Approval</div>
    </div>

    <!-- Main Content -->
    <div class="content-section">
        <p>Dear Sir/Madam,</p>
        
        <p>We hope this letter finds you well. We are writing to formally request the approval and whitelisting of a sender name for SMS communications through your network.</p>
        
        <p>Callbly is a professional communications platform that provides SMS and messaging services to businesses and organizations. We maintain strict compliance with all telecommunications regulations and best practices for SMS communications.</p>
    </div>

    <!-- Sender Name Highlight -->
    <div class="sender-name-highlight">
        <div>Requested Sender Name:</div>
        <div class="sender-name-text">{{ $sender_name }}</div>
    </div>

    <!-- User Details -->
    <div class="content-section">
        <p><strong>Client Information:</strong></p>
        <table class="user-details-table">
            <tr>
                <td>Client Name:</td>
                <td>{{ $user_name }}</td>
            </tr>
            <tr>
                <td>Email Address:</td>
                <td>{{ $user_email }}</td>
            </tr>
            <tr>
                <td>Company/Organization:</td>
                <td>{{ $company_name }}</td>
            </tr>
            <tr>
                <td>Phone Number:</td>
                <td>{{ $phone_number }}</td>
            </tr>
            <tr>
                <td>Request Date:</td>
                <td>{{ $created_at }}</td>
            </tr>
        </table>
    </div>

    <!-- Purpose and Usage -->
    <div class="content-section">
        <p><strong>Purpose and Usage:</strong></p>
        <p>The requested sender name will be used for legitimate business communications including but not limited to:</p>
        <ul style="margin-left: 20px; margin-bottom: 15px;">
            <li>Transactional notifications</li>
            <li>Account alerts and updates</li>
            <li>OTP and verification codes</li>
            <li>Service-related communications</li>
            <li>Customer engagement messages</li>
        </ul>
    </div>

    <!-- Sample Message -->
    <div class="sample-message-box">
        <div class="sample-message-title">Sample Message:</div>
        <div>"{{ $sample_message }}"</div>
    </div>

    <!-- Compliance Statement -->
    <div class="content-section">
        <p><strong>Compliance Assurance:</strong></p>
        <p>We hereby confirm that:</p>
        <ul style="margin-left: 20px; margin-bottom: 15px;">
            <li>All messages sent using this sender name will comply with telecommunications regulations</li>
            <li>We will not engage in spam or unsolicited messaging</li>
            <li>We maintain proper opt-in/opt-out mechanisms</li>
            <li>We respect all DND (Do Not Disturb) preferences</li>
            <li>We will use the sender name only for legitimate business purposes</li>
        </ul>
    </div>

    <!-- Request -->
    <div class="content-section">
        <p>We kindly request your approval to whitelist the above-mentioned sender name for SMS communications through your network. We are committed to maintaining the highest standards of messaging practices and will ensure full compliance with all applicable regulations.</p>
        
        <p>Should you require any additional information or documentation, please do not hesitate to contact us at the details provided above.</p>
        
        <p>Thank you for your time and consideration. We look forward to your positive response.</p>
    </div>

    <!-- Signature -->
    <div class="signature-section">
        <p>Yours sincerely,</p>
        <div class="signature-line"></div>
        <div class="signature-text">
            <strong>Callbly Support Team</strong><br>
            Communications Platform<br>
            Email: support@callbly.com
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        This document was automatically generated by Callbly Communications Platform<br>
        Generated on {{ $request_date }} | Reference: {{ $request_reference }}
    </div>
</body>
</html>
