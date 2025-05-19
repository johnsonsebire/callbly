<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Services\Currency\CurrencyService;
use Illuminate\Support\Facades\Log;

class PaymentWithCurrencyService
{
    protected PaystackService $paystackService;
    protected CurrencyService $currencyService;

    public function __construct(PaystackService $paystackService, CurrencyService $currencyService)
    {
        $this->paystackService = $paystackService;
        $this->currencyService = $currencyService;
    }

    /**
     * Initialize a payment transaction with currency conversion
     * 
     * @param User $user
     * @param float $amount Amount in user's currency
     * @param array $metadata Additional information about the transaction
     * @param string $callbackUrl URL to redirect after payment
     * @param string $productName Optional product name for reference
     * @return array
     */
    public function initializePayment(
        User $user, 
        float $amount, 
        array $metadata = [], 
        string $callbackUrl = '',
        string $productName = ''
    ): array {
        try {
            // Convert amount from user's currency to base currency (GHS)
            $baseAmount = $user->convertToBaseCurrency($amount);
            
            // Store original currency information in metadata
            $metadata['currency_data'] = [
                'original_currency' => $user->currency->code,
                'original_amount' => $amount,
                'exchange_rate' => $user->currency->exchange_rate,
                'base_currency' => 'GHS',
                'base_amount' => $baseAmount
            ];
            
            // Store user's billing tier in metadata
            $metadata['billing_tier'] = [
                'id' => $user->billingTier->id,
                'name' => $user->billingTier->name,
                'price_per_sms' => $user->billingTier->price_per_sms
            ];
            
            // Add user currency preference to metadata if not present
            if (!isset($metadata['user_id'])) {
                $metadata['user_id'] = $user->id;
            }

            // Initialize the transaction with the base amount
            $result = $this->paystackService->initializeTransaction(
                $baseAmount, 
                $user->email, 
                $metadata, 
                $callbackUrl
            );
            
            // If successful, add formatted amounts to the response
            if ($result['success']) {
                $result['formatted_amount'] = $user->formatAmount($amount);
                $result['amount_in_user_currency'] = $amount;
                $result['user_currency'] = $user->currency->code;
                $result['user_currency_symbol'] = $user->currency->symbol;
            }
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Multi-currency payment initialization error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'amount' => $amount,
                'currency' => $user->currency->code,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify a payment transaction and handle currency conversion
     * 
     * @param string $reference
     * @param User|null $user
     * @return array
     */
    public function verifyPayment(string $reference, ?User $user = null): array
    {
        // Verify the transaction with Paystack
        $result = $this->paystackService->verifyTransaction($reference);
        
        // If verification is successful, process currency information
        if ($result['success'] && isset($result['data'])) {
            $transaction = $result['data'];
            
            // Extract currency data from metadata if available
            $currencyData = $transaction['metadata']['currency_data'] ?? null;
            $billingTierData = $transaction['metadata']['billing_tier'] ?? null;
            
            // If we have the original currency information
            if ($currencyData) {
                $result['original_currency'] = $currencyData['original_currency'];
                $result['original_amount'] = $currencyData['original_amount'];
                
                // Format the amount in the original currency
                if ($user) {
                    $result['formatted_amount'] = $user->formatAmount($currencyData['original_amount']);
                } else {
                    // Get the currency by code if we don't have the user
                    $currency = $this->currencyService->getCurrencyByCode($currencyData['original_currency']);
                    $result['formatted_amount'] = $currency->format($currencyData['original_amount']);
                }
            }
            
            // Include billing tier information
            if ($billingTierData) {
                $result['billing_tier'] = $billingTierData;
            }
            
            // Attempt to update user's billing tier based on purchase amount
            if ($user && isset($transaction['amount'])) {
                $baseAmount = $transaction['amount'] / 100; // Convert from kobo to GHS
                $this->currencyService->updateUserBillingTier($user, $baseAmount);
            }
        }
        
        return $result;
    }

    /**
     * Generate a payment link with support for different currencies
     *
     * @param User $user
     * @param string $productName
     * @param float $amount Amount in user's currency
     * @param array $metadata
     * @return array
     */
    public function generatePaymentLink(User $user, string $productName, float $amount, array $metadata = []): array
    {
        // Convert amount from user's currency to base currency (GHS)
        $baseAmount = $user->convertToBaseCurrency($amount);
        
        // Store original currency information
        $metadata['currency_data'] = [
            'original_currency' => $user->currency->code,
            'original_amount' => $amount,
            'exchange_rate' => $user->currency->exchange_rate,
            'base_currency' => 'GHS',
            'base_amount' => $baseAmount
        ];

        // Generate payment link
        $result = $this->paystackService->generatePaymentLink(
            $productName,
            $baseAmount,
            $user->email,
            $metadata
        );
        
        // Add formatted amounts to response
        if ($result['success']) {
            $result['formatted_amount'] = $user->formatAmount($amount);
            $result['amount_in_user_currency'] = $amount;
            $result['currency'] = $user->currency->code;
        }
        
        return $result;
    }

    /**
     * Create a refund with currency conversion
     *
     * @param User $user
     * @param string $transactionReference
     * @param float|null $amount Amount in user's currency
     * @param string|null $reason
     * @return array
     */
    public function createRefund(User $user, string $transactionReference, ?float $amount = null, ?string $reason = null): array
    {
        // If amount is provided, convert it from user's currency to base currency
        $baseAmount = null;
        if ($amount !== null) {
            $baseAmount = $user->convertToBaseCurrency($amount);
        }
        
        // Request the refund
        $result = $this->paystackService->createRefund($transactionReference, $baseAmount, $reason);
        
        // Add formatted amounts to response
        if ($result['success'] && $amount !== null) {
            $result['formatted_amount'] = $user->formatAmount($amount);
            $result['amount_in_user_currency'] = $amount;
            $result['currency'] = $user->currency->code;
        }
        
        return $result;
    }
    
    /**
     * Forward other method calls to the underlying Paystack service
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->paystackService, $method], $args);
    }
}