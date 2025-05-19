# SMS Service Providers

This directory contains the implementations of various SMS service providers that can be used with the Callbly application.

## Available Providers

Currently, the following SMS providers are implemented:

- **Nalo SMS** - Default provider with full implementation of the SMS Provider Interface

## Configuration

SMS providers are configured in the `config/sms.php` file. The active provider can be set via the `SMS_PROVIDER` environment variable.

### Environment Variables

#### General SMS Configuration
```
SMS_PROVIDER=nalo
SMS_PRICE_PER_CREDIT=2.5
SMS_CREDITS_PER_SMS=1
SMS_MIN_BALANCE=1
```

#### Nalo SMS Provider
```
NALO_API_KEY=your_api_key
NALO_API_URL=https://api.nalosms.com
NALO_SENDER_ID=CALLBLY
```

## Usage

### Basic Usage

The SMS service provider can be used by resolving the `SmsProviderInterface` contract:

```php
use App\Contracts\SmsProviderInterface;

class YourClass
{
    protected $smsProvider;
    
    public function __construct(SmsProviderInterface $smsProvider)
    {
        $this->smsProvider = $smsProvider;
    }
    
    public function sendMessage()
    {
        $result = $this->smsProvider->sendSms('1234567890', 'Your message here', 'CALLBLY');
        
        if ($result['success']) {
            // Message sent successfully
            $messageId = $result['message_id'];
        }
    }
}
```

### Sending Bulk SMS

```php
$recipients = ['1234567890', '0987654321', '5555555555'];
$result = $smsProvider->sendBulkSms($recipients, 'Your bulk message here');
```

### Checking Message Status

```php
$status = $smsProvider->getMessageStatus('message_id_here');
```

### Getting Account Balance

```php
$balance = $smsProvider->getBalance();
echo "You have {$balance['credits']} credits remaining.";
```

### Calculating Credits Required

```php
$message = "This is a test message that might be longer than 160 characters...";
$recipients = 5;
$creditsNeeded = $smsProvider->calculateCreditsNeeded($message, $recipients);
echo "Sending this message to {$recipients} recipients will use {$creditsNeeded} credits.";
```

## Implementing a New Provider

To implement a new SMS provider:

1. Create a new class in the `App\Services\Sms` namespace
2. Implement the `App\Contracts\SmsProviderInterface` interface
3. Add configuration settings to `config/sms.php`
4. Update the `SmsServiceProvider` to include your new provider option

Example:

```php
namespace App\Services\Sms;

use App\Contracts\SmsProviderInterface;

class YourNewProvider implements SmsProviderInterface 
{
    // Implement all required methods...
}
```

Then update the `SmsServiceProvider`:

```php
return match ($default) {
    'nalo' => new NaloSmsProvider(),
    'your_provider' => new YourNewProvider(),
    default => new NaloSmsProvider()
};
```