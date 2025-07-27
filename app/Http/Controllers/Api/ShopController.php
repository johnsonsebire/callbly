<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServicePlan;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of available service plans with category filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlans(Request $request)
    {
        $category = $request->get('category', 'all');
        
        $query = ServicePlan::where('is_active', true);
        
        // Filter by category if not 'all'
        if ($category !== 'all') {
            $query->where('type', $category);
        }
        
        $plans = $query->orderBy('price', 'asc')->get();
        
        $user = $request->user();
        
        // Transform plans to match mobile app expectations
        $transformedPlans = $plans->map(function ($plan) use ($user) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description,
                'price' => (float) $plan->price,
                'formatted_price' => $user->formatAmount($plan->price),
                'currency' => $user->currency->code,
                'currency_symbol' => $user->currency->symbol,
                'features' => $plan->features ?: [],
                'billing_cycle' => $this->mapBillingCycle($plan->validity_days),
                'is_active' => $plan->is_active,
                'created_at' => $plan->created_at->toISOString(),
                'updated_at' => $plan->updated_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformedPlans
        ]);
    }

    /**
     * Purchase a service plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $planId
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchasePlan(Request $request, $planId)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'sometimes|string|in:paystack,card,bank_transfer,wallet',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = ServicePlan::where('id', $planId)
            ->where('is_active', true)
            ->first();

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Service plan not found'
            ], 404);
        }

        $user = auth()->user();

        // Check if user already has an active subscription for this plan
        $existingOrder = Order::where('user_id', $user->id)
            ->where('service_plan_id', $plan->id)
            ->where('status', 'completed')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingOrder) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription for this plan'
            ], 400);
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'service_plan_id' => $plan->id,
            'amount' => $plan->price,
            'status' => 'pending',
            'reference_id' => 'CALLBLY-' . strtoupper(Str::random(10)),
            'payment_method' => $request->get('payment_method', 'paystack'),
            'expires_at' => now()->addDays($plan->validity_days),
        ]);

        // For mobile app, we'll simulate successful payment for now
        // In production, this would integrate with actual payment providers
        $order->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan purchased successfully',
            'data' => [
                'order' => $order,
                'plan' => $plan,
            ]
        ]);
    }

    /**
     * Get user's active subscriptions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubscriptions()
    {
        $user = auth()->user();
        
        $subscriptions = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with('servicePlan')
            ->orderBy('created_at', 'desc')
            ->get();

        $transformedSubscriptions = $subscriptions->map(function ($order) {
            return [
                'id' => $order->id,
                'plan_id' => $order->service_plan_id,
                'plan_name' => $order->servicePlan->name ?? 'Unknown Plan',
                'status' => $order->expires_at && $order->expires_at > now() ? 'active' : 'expired',
                'expires_at' => $order->expires_at ? $order->expires_at->toISOString() : null,
                'created_at' => $order->created_at->toISOString(),
                'updated_at' => $order->updated_at->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformedSubscriptions
        ]);
    }

    /**
     * Get service plans with proper currency formatting for mobile app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServicePlans(Request $request)
    {
        try {
            $user = $request->user();
            
            $servicePlans = ServicePlan::where('is_active', true)
                ->orderBy('credits')
                ->get()
                ->map(function ($plan) use ($user) {
                    return [
                        'id' => $plan->id,
                        'name' => $plan->name,
                        'description' => $plan->description,
                        'credits' => $plan->units, // Using units as credits
                        'price' => $plan->price,
                        'formatted_price' => $user->formatAmount($plan->price),
                        'currency' => $user->currency->code,
                        'currency_symbol' => $user->currency->symbol,
                        'is_popular' => $plan->is_popular,
                        'features' => $plan->features ? (is_array($plan->features) ? $plan->features : json_decode($plan->features, true)) : [],
                        'validity_days' => $plan->validity_days,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $servicePlans
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch service plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map validity days to billing cycle for mobile app compatibility.
     *
     * @param  int  $validityDays
     * @return string
     */
    private function mapBillingCycle($validityDays)
    {
        if ($validityDays >= 365) {
            return 'yearly';
        } elseif ($validityDays >= 30) {
            return 'monthly';
        } else {
            return 'monthly'; // Default fallback
        }
    }
}
