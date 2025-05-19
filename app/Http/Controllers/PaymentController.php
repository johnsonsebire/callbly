<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Payment\PaymentWithCurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    protected PaymentWithCurrencyService $paymentService;

    /**
     * Create a new controller instance.
     */
    public function __construct(PaymentWithCurrencyService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate a payment transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'product_type' => 'required|string|in:sms_credits,call_credits,ussd_credits,virtual_number',
        ]);

        $user = Auth::user();
        $amount = $validated['amount'];
        $productType = $validated['product_type'];
        
        // Default callback URL is the verify route
        $callbackUrl = route('payment.verify');
        
        // Create metadata for the transaction
        $metadata = [
            'user_id' => $user->id,
            'product_type' => $productType,
            'timestamp' => now()->timestamp,
        ];
        
        // Prepare product name for payment
        $productName = match($productType) {
            'sms_credits' => 'SMS Credits',
            'call_credits' => 'Call Credits',
            'ussd_credits' => 'USSD Credits',
            'virtual_number' => 'Virtual Number',
            default => 'Credits Purchase'
        };
        
        try {
            // Initialize payment with the payment service
            $paymentResponse = $this->paymentService->initializePayment(
                $user,
                $amount,
                $metadata,
                $callbackUrl,
                $productName
            );
            
            if ($paymentResponse['success']) {
                // Store payment reference in session for verification
                session(['payment_reference' => $paymentResponse['reference']]);
                
                // Redirect to the payment URL
                return Redirect::away($paymentResponse['authorization_url']);
            } else {
                // Log the error
                Log::error('Payment initialization failed', [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'product_type' => $productType,
                    'error' => $paymentResponse['message'] ?? 'Unknown error',
                ]);
                
                // Redirect back with error message
                return redirect()->back()->with('error', 'Unable to initiate payment: ' . ($paymentResponse['message'] ?? 'Unknown error'));
            }
            
        } catch (\Exception $e) {
            Log::error('Payment exception', [
                'user_id' => $user->id,
                'amount' => $amount,
                'product_type' => $productType,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->back()->with('error', 'An error occurred while processing your payment: ' . $e->getMessage());
        }
    }

    /**
     * Verify a payment transaction
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        // Get the payment reference from the request or session
        $reference = $request->get('reference') ?: session('payment_reference');
        
        if (!$reference) {
            return redirect()->route('dashboard')->with('error', 'No payment reference found');
        }
        
        $user = Auth::user();
        
        try {
            // Verify the payment
            $verificationResponse = $this->paymentService->verifyPayment($reference, $user);
            
            // If payment verification is successful
            if ($verificationResponse['success'] && 
                isset($verificationResponse['data']['status']) && 
                $verificationResponse['data']['status'] === 'success') {
                
                // Get transaction details
                $transaction = $verificationResponse['data'];
                $metadata = $transaction['metadata'] ?? [];
                
                // Extract product type
                $productType = $metadata['product_type'] ?? '';
                
                // Handle different product types
                if ($productType === 'sms_credits') {
                    $this->processSmsCreditsPayment($user, $verificationResponse);
                    return redirect()->route('sms.credits')->with('success', 'SMS credits purchase successful!');
                } elseif ($productType === 'call_credits') {
                    $this->processCallCreditsPayment($user, $verificationResponse);
                    return redirect()->route('dashboard')->with('success', 'Call credits purchase successful!');
                } elseif ($productType === 'ussd_credits') {
                    $this->processUssdCreditsPayment($user, $verificationResponse);
                    return redirect()->route('dashboard')->with('success', 'USSD credits purchase successful!');
                } elseif ($productType === 'virtual_number') {
                    $this->processVirtualNumberPayment($user, $verificationResponse);
                    return redirect()->route('dashboard')->with('success', 'Virtual number purchase successful!');
                } else {
                    // Generic success for other product types
                    return redirect()->route('dashboard')->with('success', 'Payment successful!');
                }
            } else {
                // Payment verification failed
                Log::error('Payment verification failed', [
                    'user_id' => $user->id,
                    'reference' => $reference,
                    'response' => $verificationResponse,
                ]);
                
                return redirect()->route('dashboard')->with('error', 'Payment verification failed: ' . 
                    ($verificationResponse['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Payment verification exception', [
                'user_id' => $user->id,
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
            
            return redirect()->route('dashboard')->with('error', 'An error occurred while verifying your payment: ' . $e->getMessage());
        }
    }

    /**
     * Process a successful SMS credits payment
     *
     * @param User $user
     * @param array $verificationResponse
     * @return void
     */
    private function processSmsCreditsPayment(User $user, array $verificationResponse): void
    {
        $transaction = $verificationResponse['data'];
        $metadata = $transaction['metadata'] ?? [];
        $billingTier = $user->billingTier;
        
        // Get original amount in user's currency
        $originalAmount = $metadata['currency_data']['original_amount'] ?? 0;
        
        // Calculate SMS credits based on billing tier
        $smsCredits = $billingTier->calculateSmsCredits($originalAmount);
        
        // Add credits to user
        $user->sms_credits += $smsCredits;
        $user->save();
        
        // Log the transaction
        Log::info('SMS credits added to user', [
            'user_id' => $user->id,
            'added_credits' => $smsCredits,
            'total_credits' => $user->sms_credits,
            'payment_reference' => $transaction['reference'],
            'amount' => $originalAmount,
            'currency' => $user->currency->code,
        ]);
    }

    /**
     * Process a successful call credits payment
     *
     * @param User $user
     * @param array $verificationResponse
     * @return void
     */
    private function processCallCreditsPayment(User $user, array $verificationResponse): void
    {
        $transaction = $verificationResponse['data'];
        $metadata = $transaction['metadata'] ?? [];
        
        // Get original amount in user's currency
        $originalAmount = $metadata['currency_data']['original_amount'] ?? 0;
        
        // Simple calculation for call credits (can be adjusted based on business requirements)
        $callCredits = floor($originalAmount * 10); // Example: 10 credits per currency unit
        
        // Add credits to user
        $user->call_credits += $callCredits;
        $user->save();
        
        // Log the transaction
        Log::info('Call credits added to user', [
            'user_id' => $user->id,
            'added_credits' => $callCredits,
            'total_credits' => $user->call_credits,
            'payment_reference' => $transaction['reference'],
        ]);
    }

    /**
     * Process a successful USSD credits payment
     *
     * @param User $user
     * @param array $verificationResponse
     * @return void
     */
    private function processUssdCreditsPayment(User $user, array $verificationResponse): void
    {
        $transaction = $verificationResponse['data'];
        $metadata = $transaction['metadata'] ?? [];
        
        // Get original amount in user's currency
        $originalAmount = $metadata['currency_data']['original_amount'] ?? 0;
        
        // Simple calculation for USSD credits (can be adjusted based on business requirements)
        $ussdCredits = floor($originalAmount * 20); // Example: 20 credits per currency unit
        
        // Add credits to user
        $user->ussd_credits += $ussdCredits;
        $user->save();
        
        // Log the transaction
        Log::info('USSD credits added to user', [
            'user_id' => $user->id,
            'added_credits' => $ussdCredits,
            'total_credits' => $user->ussd_credits,
            'payment_reference' => $transaction['reference'],
        ]);
    }

    /**
     * Process a successful virtual number payment
     *
     * @param User $user
     * @param array $verificationResponse
     * @return void
     */
    private function processVirtualNumberPayment(User $user, array $verificationResponse): void
    {
        $transaction = $verificationResponse['data'];
        
        // Virtual number purchase would typically create a service request
        // or directly provision a number, which would be handled elsewhere.
        
        Log::info('Virtual number payment processed', [
            'user_id' => $user->id,
            'payment_reference' => $transaction['reference'],
        ]);
        
        // Implementation would depend on how virtual numbers are managed
    }
}
