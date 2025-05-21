<?php

namespace App\Notifications;

use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SmsPurchaseInvoiceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public WalletTransaction $transaction
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $user = $this->transaction->user;
        
        return (new MailMessage)
            ->view('emails.invoices.sms-purchase', [
                'transaction' => $this->transaction,
                'user' => $user,
                'smsCredits' => $this->transaction->metadata['sms_credits'] ?? 0,
                'smsRate' => $this->transaction->metadata['rate'] ?? 0,
                'invoiceDate' => $this->transaction->created_at->format('Y-m-d'),
                'invoiceNumber' => 'INV-SMS-' . $this->transaction->id,
            ])
            ->subject('Invoice: SMS Credits Purchase');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->id,
            'amount' => $this->transaction->amount,
            'reference' => $this->transaction->reference,
        ];
    }
}