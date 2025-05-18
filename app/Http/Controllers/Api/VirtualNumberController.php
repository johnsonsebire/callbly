<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ServicePlan;
use App\Models\VirtualNumber;
use App\Services\Payment\PaystackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VirtualNumberController extends Controller
{
    protected PaystackService $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Browse available virtual numbers.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function browse(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'sometimes|string|max:5',
            'type' => 'sometimes|in:local,toll-free,premium',
            'price_min' => 'sometimes|numeric|min:0',
            'price_max' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = VirtualNumber::where('status', 'available');

            if ($request->has('country_code')) {
                $query->where('country_code', $request->country_code);
            }

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('price_min')) {
                $query->where('monthly_fee', '>=', $request->price_min);
            }

            if ($request->has('price_max')) {
                $query->where('monthly_fee', '<=', $request->price_max);
            }

            $numbers = $query->orderBy('monthly_fee', 'asc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $numbers
            ]);
        } catch (\Exception $e) {
            Log::error('Virtual number browsing error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while browsing virtual numbers'
            ], 500);
        }
    }

    /**
     * Reserve a virtual number.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reserve(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number_id' => 'required|exists:virtual_numbers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $number = VirtualNumber::where('id', $request->number_id)
                ->where('status', 'available')
                ->first();

            if (!$number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Virtual number is not available'
                ], 400);
            }

            // Reserve for 30 minutes
            $reservedUntil = now()->addMinutes(30);
            
            $number->update([
                'status' => 'reserved',
                'user_id' => $user->id,
                'reserved_until' => $reservedUntil
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Virtual number reserved successfully',
                'data' => [
                    'number' => $number,
                    'reserved_until' => $reservedUntil->toIso8601String()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Virtual number reservation error: ' . $e->getMessage(), [
                'exception' => $e,
                'number_id' => $request->number_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while reserving the virtual number'
            ], 500);
        }
    }

    /**
     * Purchase a virtual number.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function purchase(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'number_id' => 'required|exists:virtual_numbers,id',
            'plan_id' => 'required|exists:service_plans,id',
            'forwarding_number' => 'required|string|max:20',
            'payment_method' => 'required|in:paystack,wallet',
            'referral_code' => 'nullable|string|exists:affiliate_referrals,referral_code',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $number = VirtualNumber::where('id', $request->number_id)
                ->where(function ($query) use ($user) {
                    $query->where('status', 'available')
                        ->orWhere(function ($q) use ($user) {
                            $q->where('status', 'reserved')
                              ->where('user_id', $user->id)
                              ->where('reserved_until', '>=', now());
                        });
                })
                ->first();

            if (!$number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Virtual number is not available or reservation has expired'
                ], 400);
            }

            $plan = ServicePlan::findOrFail($request->plan_id);

            // Calculate total cost (number monthly fee + plan cost)
            $totalCost = $number->monthly_fee + $plan->price;

            // Handle payment based on selected method
            if ($request->payment_method === 'wallet') {
                // Check if user has sufficient balance
                if ($user->account_balance < $totalCost) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient account balance'
                    ], 400);
                }

                // Process wallet payment
                $paymentSuccessful = true;
                $paymentDetails = [
                    'method' => 'wallet',
                    'transaction_date' => now()->toIso8601String(),
                ];

            } else { // paystack
                // Initialize Paystack transaction
                $reference = 'NUM_' . Str::uuid()->toString();
                $paymentResult = $this->paystackService->initializeTransaction(
                    $totalCost,
                    $user->email,
                    [
                        'number_id' => $number->id,
                        'plan_id' => $plan->id,
                        'user_id' => $user->id
                    ],
                    route('payment.callback', ['reference' => $reference])
                );

                if (!$paymentResult || !isset($paymentResult['success']) || !$paymentResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment initialization failed',
                        'error' => $paymentResult['message'] ?? 'Unknown error'
                    ], 500);
                }

                // For Paystack, we'll create a pending order and return the authorization URL
                $order = Order::create([
                    'user_id' => $user->id,
                    'service_plan_id' => $plan->id,
                    'virtual_number_id' => $number->id,
                    'amount' => $totalCost,
                    'reference_id' => $reference,
                    'status' => 'pending',
                    'payment_method' => 'paystack',
                    'payment_details' => [
                        'authorization_url' => $paymentResult['data']['authorization_url'] ?? null,
                        'access_code' => $paymentResult['data']['access_code'] ?? null,
                    ],
                    'affiliate_referral_id' => null, // Will be updated if referral is valid
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated',
                    'data' => [
                        'order_id' => $order->id,
                        'reference' => $reference,
                        'amount' => $totalCost,
                        'authorization_url' => $paymentResult['data']['authorization_url'] ?? null,
                    ]
                ]);
            }

            // If wallet payment was successful or for immediate processing methods
            if (isset($paymentSuccessful) && $paymentSuccessful) {
                // Create order
                $reference = 'NUM_WALLET_' . Str::uuid()->toString();
                $order = Order::create([
                    'user_id' => $user->id,
                    'service_plan_id' => $plan->id,
                    'virtual_number_id' => $number->id,
                    'amount' => $totalCost,
                    'reference_id' => $reference,
                    'status' => 'completed',
                    'payment_method' => 'wallet',
                    'payment_details' => $paymentDetails,
                    'paid_at' => now(),
                    'affiliate_referral_id' => null, // Will be updated if referral is valid
                ]);

                // Update virtual number
                $number->update([
                    'user_id' => $user->id,
                    'status' => 'active',
                    'forwarding_number' => $request->forwarding_number,
                    'expires_at' => now()->addDays($plan->validity_days),
                    'reserved_until' => null,
                ]);

                // Deduct from user's wallet
                $user->update([
                    'account_balance' => $user->account_balance - $totalCost
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Virtual number purchased successfully',
                    'data' => [
                        'order_id' => $order->id,
                        'number' => $number->fresh(),
                        'expires_at' => $number->expires_at->toIso8601String(),
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Virtual number purchase error: ' . $e->getMessage(), [
                'exception' => $e,
                'number_id' => $request->number_id,
                'plan_id' => $request->plan_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while purchasing the virtual number'
            ], 500);
        }
    }

    /**
     * Update forwarding settings for a virtual number.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateForwarding(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'forwarding_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $number = VirtualNumber::where('id', $id)
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (!$number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Virtual number not found or not active'
                ], 404);
            }

            $number->update([
                'forwarding_number' => $request->forwarding_number
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Forwarding number updated successfully',
                'data' => $number
            ]);
        } catch (\Exception $e) {
            Log::error('Virtual number forwarding update error: ' . $e->getMessage(), [
                'exception' => $e,
                'number_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating forwarding settings'
            ], 500);
        }
    }

    /**
     * Renew a virtual number subscription.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function renew(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:service_plans,id',
            'payment_method' => 'required|in:paystack,wallet',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $number = VirtualNumber::where('id', $id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['active', 'expired'])
                ->first();

            if (!$number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Virtual number not found or not eligible for renewal'
                ], 404);
            }

            $plan = ServicePlan::findOrFail($request->plan_id);

            // Calculate cost (number monthly fee + plan cost)
            $totalCost = $number->monthly_fee + $plan->price;

            // Handle payment based on selected method
            if ($request->payment_method === 'wallet') {
                // Check if user has sufficient balance
                if ($user->account_balance < $totalCost) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient account balance'
                    ], 400);
                }

                // Process wallet payment
                $paymentSuccessful = true;
                $paymentDetails = [
                    'method' => 'wallet',
                    'transaction_date' => now()->toIso8601String(),
                ];
            } else { // paystack
                // Initialize Paystack transaction
                $reference = 'RENEW_' . Str::uuid()->toString();
                $paymentResult = $this->paystackService->initializeTransaction(
                    $totalCost,
                    $user->email,
                    [
                        'number_id' => $number->id,
                        'plan_id' => $plan->id,
                        'user_id' => $user->id,
                        'action' => 'renewal'
                    ],
                    route('payment.callback', ['reference' => $reference])
                );

                if (!$paymentResult || !isset($paymentResult['success']) || !$paymentResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment initialization failed',
                        'error' => $paymentResult['message'] ?? 'Unknown error'
                    ], 500);
                }

                // For Paystack, we'll create a pending order and return the authorization URL
                $order = Order::create([
                    'user_id' => $user->id,
                    'service_plan_id' => $plan->id,
                    'virtual_number_id' => $number->id,
                    'amount' => $totalCost,
                    'reference_id' => $reference,
                    'status' => 'pending',
                    'payment_method' => 'paystack',
                    'payment_details' => [
                        'authorization_url' => $paymentResult['data']['authorization_url'] ?? null,
                        'access_code' => $paymentResult['data']['access_code'] ?? null,
                    ],
                    'notes' => 'Renewal',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Renewal payment initiated',
                    'data' => [
                        'order_id' => $order->id,
                        'reference' => $reference,
                        'amount' => $totalCost,
                        'authorization_url' => $paymentResult['data']['authorization_url'] ?? null,
                    ]
                ]);
            }

            // If wallet payment was successful or for immediate processing methods
            if (isset($paymentSuccessful) && $paymentSuccessful) {
                // Create order
                $reference = 'RENEW_WALLET_' . Str::uuid()->toString();
                $order = Order::create([
                    'user_id' => $user->id,
                    'service_plan_id' => $plan->id,
                    'virtual_number_id' => $number->id,
                    'amount' => $totalCost,
                    'reference_id' => $reference,
                    'status' => 'completed',
                    'payment_method' => 'wallet',
                    'payment_details' => $paymentDetails,
                    'paid_at' => now(),
                    'notes' => 'Renewal',
                ]);

                // Update virtual number
                $expiresAt = $number->status === 'expired' || $number->expires_at < now()
                    ? now()->addDays($plan->validity_days)
                    : $number->expires_at->addDays($plan->validity_days);

                $number->update([
                    'status' => 'active',
                    'expires_at' => $expiresAt,
                ]);

                // Deduct from user's wallet
                $user->update([
                    'account_balance' => $user->account_balance - $totalCost
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Virtual number renewed successfully',
                    'data' => [
                        'order_id' => $order->id,
                        'number' => $number->fresh(),
                        'expires_at' => $number->expires_at->toIso8601String(),
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Virtual number renewal error: ' . $e->getMessage(), [
                'exception' => $e,
                'number_id' => $id,
                'plan_id' => $request->plan_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while renewing the virtual number'
            ], 500);
        }
    }

    /**
     * Get user's virtual numbers.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserNumbers(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $numbers = VirtualNumber::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $numbers
            ]);
        } catch (\Exception $e) {
            Log::error('Get user virtual numbers error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving virtual numbers'
            ], 500);
        }
    }
}
