# Sender Name Whitelist PDF Generation Feature

## Overview

The Sender Name Whitelist PDF Generation feature automates the process of creating and sending whitelist request documents for SMS sender names. This feature generates professional PDF documents on company letterhead and can automatically email them to designated telecom providers for approval.

## Features

### 1. Automatic PDF Generation
- Professional PDF documents generated on Callbly letterhead
- Dynamic content including sender name and randomized sample messages
- Proper formatting and branding consistent with company standards

### 2. System Settings Configuration
- **Auto-Send Toggle**: Enable/disable automatic email sending
- **Email Configuration**: Set multiple email addresses for automatic delivery
- **Manual Override**: Download PDFs manually when auto-send is disabled

### 3. Admin Interface Integration
- Download PDF buttons on sender names management page
- Send email buttons for manual triggering
- Integration with existing approval workflow

## How It Works

### Automatic Process (When Auto-Send is Enabled)
1. User requests a new sender name
2. System automatically generates a PDF whitelist request document
3. PDF is emailed to all configured recipient addresses
4. Admin can still manually download or send additional copies

### Manual Process (When Auto-Send is Disabled)
1. User requests a new sender name
2. Admin can download PDF from the sender names management page
3. Admin can manually send emails using the send button
4. Full control over when and how documents are distributed

## PDF Document Contents

The generated PDF includes:

### Header Section
- Callbly company letterhead
- Document title and reference number
- Date of request

### Request Details
- Requested sender name (highlighted)
- Client information (name, email, company, phone)
- Purpose and usage description
- Sample message (randomized from predefined list)

### Compliance Information
- Regulatory compliance statements
- Terms and conditions
- Professional signature block

### Sample Messages
The system randomly selects from predefined sample messages:
- OTP verification codes
- Account balance notifications
- Appointment reminders
- Registration confirmations
- Security alerts
- Order confirmations
- Payment confirmations
- Subscription reminders

## Configuration

### System Settings
Navigate to **Admin > System Settings** to configure:

1. **Auto-Send Whitelist Requests**
   - Enable: Automatically send PDFs via email
   - Disable: Manual download and sending only

2. **Notification Email Addresses**
   - Enter comma-separated email addresses
   - Example: `approvals@telco.com, whitelist@provider.com`
   - Supports multiple recipients

### Email Configuration
Ensure your Laravel mail configuration is properly set up:
- SMTP settings in `.env` file
- Mail driver configuration
- Sender email address configured

## Usage Instructions

### For Super Admins

#### Configuring Auto-Send
1. Go to **Admin > System Settings**
2. Enable "Auto-Send Whitelist Requests"
3. Enter email addresses in "Notification Email Addresses"
4. Save settings

#### Managing Sender Name Requests
1. Go to **Admin > Sender Names**
2. For each sender name, you can:
   - **Download PDF**: Click the download icon to get the PDF
   - **Send Email**: Click the send icon to email the PDF
   - **Approve/Reject**: Standard approval workflow

#### Manual Operations
- Download individual PDFs anytime
- Send emails manually even when auto-send is enabled
- Override automatic settings for specific cases

### For Users
The feature is transparent to end users:
1. Request sender names as usual through SMS > Sender Names
2. PDFs are automatically generated in the background
3. Admin handles the approval process with enhanced documentation

## Technical Implementation

### Core Components

1. **SenderNameWhitelistPdfService**
   - PDF generation using DomPDF
   - Email sending functionality
   - Template data preparation

2. **PDF Template**
   - Professional Blade template (`pdfs.sender-name-whitelist-request`)
   - Responsive design for print
   - Dynamic content integration

3. **Email Template**
   - Email notification template (`emails.sender-name-whitelist-request`)
   - PDF attachment handling
   - Professional formatting

4. **Model Events**
   - Automatic triggering on sender name creation
   - Error handling and logging
   - Background processing

### File Storage
- PDFs stored in `storage/app/sender-name-requests/`
- Automatic cleanup after email sending
- Organized by timestamp for tracking

### Error Handling
- Comprehensive logging for all operations
- Graceful fallback when auto-send fails
- Admin notifications for critical errors

## Troubleshooting

### Common Issues

#### PDFs Not Generating
- Check DomPDF installation: `composer require barryvdh/laravel-dompdf`
- Verify storage permissions: `storage/app/` must be writable
- Check Laravel logs: `storage/logs/laravel.log`

#### Emails Not Sending
- Verify mail configuration in `.env`
- Check email addresses format in system settings
- Ensure SMTP credentials are correct
- Test with Laravel Tinker: `Mail::raw('test', function($m) { $m->to('test@example.com'); });`

#### Permission Issues
- Super admin role required for configuration
- Check user permissions and roles
- Verify middleware configuration

### Debugging Commands

```bash
# Test PDF generation
php artisan test:sender-name-pdf

# Check system settings
php artisan tinker
>>> App\Models\SystemSetting::where('key', 'LIKE', 'sender_name_%')->get();

# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($m) { $m->to('test@example.com')->subject('Test'); });
```

## Security Considerations

### Data Protection
- User information included in PDFs is business-necessary
- PDFs contain only approval-relevant data
- Temporary storage with automatic cleanup

### Access Control
- Super admin access required for configuration
- Role-based permissions enforced
- Audit logging for all operations

### Email Security
- Secure SMTP recommended
- Email addresses validated before use
- Error handling prevents information disclosure

## Maintenance

### Regular Tasks
- Monitor email delivery success rates
- Clean up old PDF files if needed
- Update sample messages as required
- Review and update email recipient lists

### Updates and Modifications
- PDF template: `resources/views/pdfs/sender-name-whitelist-request.blade.php`
- Email template: `resources/views/emails/sender-name-whitelist-request.blade.php`
- Sample messages: `SenderNameWhitelistPdfService::$sampleMessages`
- System settings: Admin interface

## API Integration

The feature integrates seamlessly with existing APIs:
- Sender name creation triggers automatic PDF generation
- RESTful endpoints maintain compatibility
- Background processing doesn't affect response times

## Future Enhancements

Potential improvements:
- Multiple PDF templates for different providers
- Batch processing for multiple sender names
- Integration with telecom provider APIs
- Advanced tracking and analytics
- Custom letterhead upload functionality

## Support

For technical support or feature requests:
- Check Laravel logs for error details
- Use debugging commands for troubleshooting
- Review system settings configuration
- Contact development team for assistance

---

*Last updated: July 15, 2025*
*Version: 1.0*
