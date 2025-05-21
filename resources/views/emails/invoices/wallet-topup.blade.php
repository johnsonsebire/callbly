@extends('emails.layouts.master')

@section('title', 'Receipt: Wallet Top-up')

@section('content')
    <h1 class="email-heading">Wallet Top-up Receipt</h1>
    
    <p class="email-text">Hello {{ $user->name }},</p>
    
    <p class="email-text">Thank you for your wallet top-up payment. Your funds have been successfully added to your account.</p>
    
    <div class="email-section">
        <div class="email-card">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding-bottom: 12px;">
                        <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin: 0;">Receipt #{{ $invoiceNumber }}</h3>
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
                    <th style="text-align: right; padding: 10px 12px; font-size: 14px; font-weight: 600; color: #181C32; border-bottom: 1px solid #E4E6EF;">Amount</th>
                </tr>
                <tr>
                    <td style="padding: 12px; font-size: 14px; color: #3F4254; border-bottom: 1px solid #E4E6EF;">Wallet Top-up</td>
                    <td style="text-align: right; padding: 12px; font-size: 14px; color: #3F4254; border-bottom: 1px solid #E4E6EF;">{{ $user->currency->symbol }}{{ number_format($transaction->amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right; padding: 12px; font-size: 14px; font-weight: 600; color: #181C32;">Total:</td>
                    <td style="text-align: right; padding: 12px; font-size: 14px; font-weight: 600; color: #181C32;">{{ $user->currency->symbol }}{{ number_format($transaction->amount, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <div class="email-section">
        <div class="email-card" style="background-color: #F1FAFF; border-color: #B7E2FF;">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Payment Information</h3>
            <p class="email-text mb-2"><strong>Payment Method:</strong> {{ $paymentMethod }}</p>
            <p class="email-text mb-2"><strong>Status:</strong> <span style="color: #50CD89; font-weight: 600;">Completed</span></p>
            <p class="email-text mb-0"><strong>Reference:</strong> {{ $transaction->reference }}</p>
        </div>
    </div>
    
    <div class="email-section">
        <div class="notice" style="background-color: #E8FFF3; border: 1px dashed #50CD89; border-radius: 6px; padding: 16px;">
            <p style="font-size: 14px; color: #3F4254; margin: 0;">Your wallet has been successfully topped up. The funds are now available in your account and can be used for SMS credits, call credits, and other services.</p>
        </div>
    </div>
    
    <p class="email-text">If you have any questions regarding this transaction, please contact our support team at <a href="mailto:support@callbly.com">support@callbly.com</a>.</p>
    
    <p class="email-text mb-0">Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection