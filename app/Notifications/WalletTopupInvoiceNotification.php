<?php

namespace App\Notifications;

use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletTopupInvoiceNotification extends Notification implements ShouldQueue
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
        $paymentMethod = $this->transaction->metadata['payment_method'] ?? 'online payment';
        
        return (new MailMessage)
            ->view('emails.invoices.wallet-topup', [
                'transaction' => $this->transaction,
                'user' => $user,
                'paymentMethod' => ucfirst(str_replace('_', ' ', $paymentMethod)),
                'invoiceDate' => $this->transaction->created_at->format('Y-m-d'),
                'invoiceNumber' => 'INV-WAL-' . $this->transaction->id,
            ])
            ->subject('Receipt: Wallet Top-up');
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