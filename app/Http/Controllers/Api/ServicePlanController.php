<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ServicePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServicePlanController extends Controller
{
    /**
     * Display a listing of available service plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $plans = ServicePlan::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    /**
     * Display the specified service plan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $plan = ServicePlan::where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Service plan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $plan
        ]);
    }

    /**
     * Purchase a service plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchase(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|in:paystack,card,bank_transfer',
            'referral_code' => 'nullable|string|exists:affiliate_referrals,referral_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = ServicePlan::where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Service plan not found'
            ], 404);
        }

        $user = auth()->user();
        $referralCode = $request->referral_code;
        $referralId = null;

        // Check if referral code is valid
        if ($referralCode) {
            $referral = \App\Models\AffiliateReferral::where('referral_code', $referralCode)->first();
            if ($referral) {
                $referralId = $referral->id;
            }
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'service_plan_id' => $plan->id,
            'amount' => $plan->price,
            'status' => 'pending',
            'reference_id' => 'CALLBLY-' . strtoupper(Str::random(10)),
            'payment_method' => $request->payment_method,
            'affiliate_referral_id' => $referralId,
        ]);

        // Generate payment link based on payment method
        $paymentUrl = $this->generatePaymentUrl($order, $request->payment_method);

        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'data' => [
                'order' => $order,
                'payment_url' => $paymentUrl,
            ]
        ]);
    }

    /**
     * Generate payment URL based on payment method.
     *
     * @param  \App\Models\Order  $order
     * @param  string  $paymentMethod
     * @return string
     */
    private function generatePaymentUrl($order, $paymentMethod)
    {
        $baseUrl = config('app.url');
        
        switch ($paymentMethod) {
            case 'paystack':
                // In a real implementation, this would integrate with Paystack's API
                return "https://checkout.paystack.com/{$order->reference_id}";
                
            case 'card':
                // Direct card payment using Stripe or similar
                return "{$baseUrl}/payment/card/{$order->reference_id}";
                
            case 'bank_transfer':
                // Bank transfer details page
                return "{$baseUrl}/payment/bank-transfer/{$order->reference_id}";
                
            default:
                return "{$baseUrl}/payment/checkout/{$order->reference_id}";
        }
    }
}
