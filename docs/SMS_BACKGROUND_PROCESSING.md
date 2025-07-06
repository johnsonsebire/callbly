# SMS Background Processing Documentation

## Overview

The SMS system has been enhanced with background processing to improve user experience by preventing long page loads during bulk SMS campaigns. SMS messages are now processed asynchronously using Laravel's queue system.

## Key Features

### 1. Background Processing
- **Single SMS**: Processed immediately via `SendSingleSmsJob`
- **Bulk SMS**: Processed in background via `SendBulkSmsJob`
- **Credit Deduction**: Happens before job dispatch to prevent double billing
- **Real-time Updates**: Campaign status updates via AJAX polling

### 2. Queue Jobs

#### SendSingleSmsJob
- Handles single SMS messages
- Timeout: 60 seconds
- Retries: 3 times with 5-second backoff
- Queue: `sms`

#### SendBulkSmsJob
- Handles bulk SMS campaigns
- Timeout: 300 seconds (5 minutes)
- Retries: 3 times with 5-second backoff
- Queue: `sms`

### 3. Campaign Statuses
- **pending**: Campaign created, waiting to be processed
- **processing**: Job is actively sending messages
- **completed**: All messages processed successfully
- **failed**: Campaign failed (job failed or all messages failed)

### 4. Notifications
- Email notifications sent when campaigns complete
- Database notifications for tracking
- Includes campaign statistics and success rates

### 5. Real-time Monitoring
- Campaign details page polls for status updates every 3 seconds
- Progress bars and statistics update automatically
- Page refreshes when campaign completes to show final results

## Usage

### Starting Queue Workers

```bash
# Start SMS queue worker (recommended for production)
php artisan sms:work --daemon

# Start standard queue worker
php artisan queue:work --queue=sms,notifications,default --timeout=300 --tries=3

# Process only available jobs then stop
php artisan sms:work
```

### Monitoring

1. **Campaign Details Page**: Real-time status updates
2. **Queue Status Endpoint**: `/sms/queue/status` for monitoring
3. **Logs**: Check `storage/logs/laravel.log` for detailed processing logs

## Technical Implementation

### Controllers
- `SmsController@send()`: Dispatches jobs instead of processing directly
- `SmsController@getCampaignStatus()`: AJAX endpoint for real-time updates
- `SmsController@getQueueStatus()`: Monitor queue health

### Database Changes
- Added `started_at` field to `sms_campaigns` table
- Uses existing `jobs` and `failed_jobs` tables for queue management
- Added `notifications` table for email/database notifications

### Credit Management
- Credits deducted before job dispatch (not after completion)
- Prevents double billing if jobs retry
- Uses existing team credit sharing logic

### Error Handling
- Failed jobs retry up to 3 times
- Campaign marked as failed after all retries exhausted
- Error messages stored in campaign and recipient records
- Notifications sent for both successful and failed campaigns

## Configuration

### Queue Configuration
Edit `config/queue.php`:
- Default connection: `database`
- Job timeout: 300 seconds for bulk SMS
- Retry attempts: 3

### Environment Variables
```env
QUEUE_CONNECTION=database
QUEUE_FAILED_DRIVER=database
```

## Benefits

1. **Improved UX**: No more waiting on pages during bulk sending
2. **Better Reliability**: Job retries handle temporary failures
3. **Scalability**: Multiple workers can process jobs in parallel
4. **Monitoring**: Real-time status updates and notifications
5. **Error Recovery**: Failed jobs can be retried manually

## Troubleshooting

### Common Issues

1. **Jobs not processing**: Ensure queue worker is running
2. **Slow processing**: Check if enough workers are running
3. **Failed jobs**: Check `failed_jobs` table and logs
4. **Credit issues**: Verify credits deducted before job dispatch

### Commands

```bash
# Check queue status
php artisan queue:monitor

# Retry failed jobs
php artisan queue:retry all

# Clear all jobs
php artisan queue:clear

# Restart queue workers (for code changes)
php artisan queue:restart
```

## Performance Considerations

- Queue workers should run as supervised processes in production
- Consider using Redis for better queue performance with high volume
- Monitor memory usage of long-running workers
- Use multiple workers for parallel processing

## Security

- Jobs run with the campaign owner's permissions
- Credit deduction happens in web request (authenticated context)
- Queue processing doesn't require authentication (uses stored campaign data)
