@extends('emails.layouts.master')

@section('title', 'Invoice: SMS Credits Purchase')

@section('content')
    <h1 class="email-heading">SMS Credits Purchase Invoice</h1>
    
    <p class="email-text">Hello {{ $user->name }},</p>
    
    <p class="email-text">Thank you for your SMS credits purchase. Below are the details of your transaction.</p>
    
    <div class="email-section">
        <div class="email-card">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding-bottom: 12px;">
                        <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin: 0;">Invoice #{{ $invoiceNumber }}</h3>
                    </td>
                    <td style="text-align: right; padding-bottom: 12px;">
                        <span style="font-size: 13px; color: #7E8299;">Date: {{ $invoiceDate }}</span>
                    </td>
                </tr>
            </table>
            <hr style="border: 0; height: 1px; background-color: #E4E6EF; margin-top: 0; margin-bottom: 16px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="background-color: #F9FAFB;">
                    <th style="text-align: left; padding: 10px 12px; font-size: 14px; font-weight: 600; color: #181C32; border-bottom: 1px solid #E4E6EF;">Description</th>
                    <th style="text-align: right; padding: 10px 12px; font-size: 14px; font-weight: 600; color: #181C32; border-bottom: 1px solid #E4E6EF;">Quantity</th>
                    <th style="text-align: right; padding: 10px 12px; font-size: 14px; font-weight: 600; color: #181C32; border-bottom: 1px solid #E4E6EF;">Rate</th>
                    <th style="text-align: right; padding: 10px 12px; font-size: 14px; font-weight: 600; color: #181C32; border-bottom: 1px solid #E4E6EF;">Amount</th>
                </tr>
                <tr>
                    <td style="padding: 12px; font-size: 14px; color: #3F4254; border-bottom: 1px solid #E4E6EF;">SMS Credits</td>
                    <td style="text-align: right; padding: 12px; font-size: 14px; color: #3F4254; border-bottom: 1px solid #E4E6EF;">{{ number_format($smsCredits) }}</td>
                    <td style="text-align: right; padding: 12px; font-size: 14px; color: #3F4254; border-bottom: 1px solid #E4E6EF;">{{ $user->currency->symbol }}{{ number_format($smsRate, 3) }}</td>
                    <td style="text-align: right; padding: 12px; font-size: 14px; color: #3F4254; border-bottom: 1px solid #E4E6EF;">{{ $user->currency->symbol }}{{ number_format($transaction->amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right; padding: 12px; font-size: 14px; font-weight: 600; color: #181C32;">Total:</td>
                    <td style="text-align: right; padding: 12px; font-size: 14px; font-weight: 600; color: #181C32;">{{ $user->currency->symbol }}{{ number_format($transaction->amount, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="email-section">
        <div class="email-card" style="background-color: #F1FAFF; border-color: #B7E2FF;">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Payment Information</h3>
            <p class="email-text mb-2"><strong>Payment Method:</strong> Wallet Balance</p>
            <p class="email-text mb-2"><strong>Status:</strong> <span style="color: #50CD89; font-weight: 600;">Paid</span></p>
            <p class="email-text mb-0"><strong>Reference:</strong> {{ $transaction->reference }}</p>
        </div>
    </div>
    
    <div class="email-section">
        <div class="notice" style="background-color: #FFF8DD; border: 1px dashed #FFC700; border-radius: 6px; padding: 16px;">
            <p style="font-size: 14px; color: #3F4254; margin: 0;">Your SMS credits have been added to your account balance. You can now use these credits to send messages through your dashboard.</p>
        </div>
    </div>
    
    <p class="email-text">If you have any questions regarding this invoice, please contact our support team at <a href="mailto:support@callbly.com">support@callbly.com</a>.</p>
    
    <p class="email-text mb-0">Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection