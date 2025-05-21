@extends('emails.layouts.master')

@section('title', 'Payment Receipt')

@section('content')
    <h1 class="email-heading">Payment Receipt</h1>
    
    <p class="email-text">Hello {{ $user->name }},</p>
    
    <p class="email-text">Thank you for your payment. Please find your receipt details below.</p>
    
    <div class="email-section">
        <div class="email-card" style="background-color: #F9FAFB; border-color: #E4E6EF;">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 16px;">Receipt #{{ $invoice->receipt_number }}</h3>
            
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; margin-bottom: 16px;">
                <div style="margin-bottom: 8px; min-width: 280px;">
                    <p style="margin: 0 0 4px; color: #7E8299; font-size: 13px;">Date</p>
                    <p style="margin: 0; font-weight: 500;">{{ $invoice->created_at->format('M d, Y') }}</p>
                </div>
                <div style="margin-bottom: 8px;">
                    <p style="margin: 0 0 4px; color: #7E8299; font-size: 13px;">Payment Method</p>
                    <p style="margin: 0; font-weight: 500;">{{ ucfirst($invoice->payment_method) }}</p>
                </div>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px;">
                <thead>
                    <tr style="border-bottom: 1px solid #E4E6EF;">
                        <th style="text-align: left; padding: 8px 0; color: #7E8299; font-size: 13px; font-weight: 500;">Item</th>
                        <th style="text-align: right; padding: 8px 0; color: #7E8299; font-size: 13px; font-weight: 500;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #E4E6EF;">
                        <td style="text-align: left; padding: 12px 0;">
                            <p style="margin: 0; font-weight: 500;">{{ $invoice->description }}</p>
                            @if($invoice->details)
                                <p style="margin: 4px 0 0; font-size: 13px; color: #7E8299;">{{ $invoice->details }}</p>
                            @endif
                        </td>
                        <td style="text-align: right; padding: 12px 0; font-weight: 500;">
                            {{ $invoice->currency->symbol }}{{ number_format($invoice->amount, 2) }}
                        </td>
                    </tr>
                    
                    @if($invoice->tax_amount > 0)
                    <tr style="border-bottom: 1px solid #E4E6EF;">
                        <td style="text-align: left; padding: 12px 0;">Tax ({{ $invoice->tax_rate }}%)</td>
                        <td style="text-align: right; padding: 12px 0; font-weight: 500;">
                            {{ $invoice->currency->symbol }}{{ number_format($invoice->tax_amount, 2) }}
                        </td>
                    </tr>
                    @endif
                    
                    <tr>
                        <td style="text-align: left; padding: 12px 0; font-weight: 600;">Total</td>
                        <td style="text-align: right; padding: 12px 0; font-weight: 600; color: #181C32;">
                            {{ $invoice->currency->symbol }}{{ number_format($invoice->total_amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div style="background-color: #F1FAFF; border: 1px dashed #009EF7; border-radius: 6px; padding: 16px; margin-top: 8px;">
                <p style="margin: 0; color: #181C32; font-weight: 600; font-size: 14px;">Transaction ID: {{ $invoice->transaction_id }}</p>
                <p style="margin: 4px 0 0; font-size: 13px; color: #7E8299;">Please reference this ID for any inquiries.</p>
            </div>
        </div>
    </div>
    
    <div class="email-section">
        <div class="email-card">
            <h3 style="font-size: 16px; font-weight: 600; color: #181C32; margin-top: 0; margin-bottom: 10px;">Need Help?</h3>
            <p class="email-text mb-0">If you have any questions about this receipt, please contact our support team at <a href="mailto:billing@callbly.com" style="color: #009ef7; text-decoration: none;">billing@callbly.com</a>.</p>
        </div>
    </div>
    
    <p class="email-text mb-0">Thank you for your business!<br>The Callbly Team</p>
@endsection