<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReferral;
use App\Models\Order;
use App\Models\VirtualNumber;
use App\Services\Payment\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaystackService $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Handle Paystack payment callback.
     *
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback(Request $request, string $reference)
    {
        try {
            // Verify transaction with Paystack
            $paymentResult = $this->paystackService->verifyTransaction($reference);

            if (!$paymentResult['success']) {
                // Payment failed
                Log::error('Payment verification failed', [
                    'reference' => $reference,
                    'result' => $paymentResult,
                ]);
                
                return redirect()->route('payment.failed', ['reference' => $reference]);
            }

            // Find the order by reference
            $order = Order::where('reference_id', $reference)->first();

            if (!$order) {
                Log::error('Order not found for reference', [
                    'reference' => $reference,
                ]);
                
                return redirect()->route('payment.error', ['error' => 'order_not_found']);
            }

            // Update order status
            $order->update([
                'status' => 'completed',
                'payment_details' => array_merge($order->payment_details ?? [], [
                    'paystack_reference' => $paymentResult['data']['reference'] ?? null,
                    'transaction_id' => $paymentResult['data']['id'] ?? null,
                    'paid_at' => now()->toIso8601String(),
                ]),
                'paid_at' => now(),
            ]);

            // Process order based on type
            if ($order->virtual_number_id) {
                $virtualNumber = VirtualNumber::find($order->virtual_number_id);
                $servicePlan = $order->servicePlan;
                
                if ($virtualNumber && $servicePlan) {
                    // Check if it's a renewal
                    if (isset($order->notes) && $order->notes === 'Renewal') {
                        $expiresAt = $virtualNumber->status === 'expired' || $virtualNumber->expires_at < now()
                            ? now()->addDays($servicePlan->validity_days)
                            : $virtualNumber->expires_at->addDays($servicePlan->validity_days);
                        
                        $virtualNumber->update([
                            'status' => 'active',
                            'expires_at' => $expiresAt,
                        ]);
                    } else {
                        // New purchase
                        $virtualNumber->update([
                            'user_id' => $order->user_id,
                            'status' => 'active',
                            'expires_at' => now()->addDays($servicePlan->validity_days),
                            'reserved_until' => null,
                        ]);
                    }
                }
            }

            // Handle referral if any
            if (isset($paymentResult['data']['metadata']['referral_code'])) {
                $this->processReferral($order, $paymentResult['data']['metadata']['referral_code']);
            }

            return redirect()->route('payment.success', ['reference' => $reference]);
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'reference' => $reference,
                'exception' => $e,
            ]);
            
            return redirect()->route('payment.error', ['error' => 'server_error']);
        }
    }

    /**
     * Handle Paystack webhook.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function paystackWebhook(Request $request): JsonResponse
    {
        try {
            // Verify Paystack webhook signature
            $payload = $request->all();
            
            // Process webhook with our service
            $result = $this->paystackService->processWebhook($payload);
            
            return response()->json([
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Paystack webhook error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            
            return response()->json([
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Process referral code and update relevant records.
     *
     * @param Order $order
     * @param string $referralCode
     * @return void
     */
    private function processReferral(Order $order, string $referralCode): void
    {
        try {
            // Find referral
            $referral = AffiliateReferral::where('referral_code', $referralCode)->first();
            
            if (!$referral) {
                return;
            }
            
            // Update order with referral ID
            $order->update([
                'affiliate_referral_id' => $referral->id
            ]);
            
            // Calculate commission
            $commission = $referral->calculateCommission($order->amount);
            
            // Update referral stats
            $referral->update([
                'conversions' => $referral->conversions + 1,
                'earnings' => $referral->earnings + $commission,
                'pending_amount' => $referral->pending_amount + $commission,
            ]);
            
            // Log the referral conversion
            Log::info('Referral conversion processed', [
                'referral_id' => $referral->id,
                'order_id' => $order->id,
                'commission' => $commission,
            ]);
        } catch (\Exception $e) {
            Log::error('Referral processing error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'referral_code' => $referralCode,
                'exception' => $e,
            ]);
        }
    }
}
