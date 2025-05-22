<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReferral;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    /**
     * Display the affiliate dashboard data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        // Get user's referral link
        $referral = AffiliateReferral::firstOrCreate(
            ['user_id' => $user->id],
            ['referral_code' => $this->generateReferralCode($user)]
        );
        
        // Calculate total earnings
        $totalEarnings = AffiliateCommission::where('affiliate_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');
            
        // Calculate pending earnings
        $pendingEarnings = AffiliateCommission::where('affiliate_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');
            
        // Get recent commissions
        $recentCommissions = AffiliateCommission::where('affiliate_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get referral stats
        $totalReferrals = AffiliateCommission::where('affiliate_id', $user->id)
            ->distinct('referred_user_id')
            ->count();
            
        return response()->json([
            'success' => true,
            'data' => [
                'referral_link' => config('app.url') . '/register?ref=' . $referral->referral_code,
                'referral_code' => $referral->referral_code,
                'total_earnings' => $totalEarnings,
                'pending_earnings' => $pendingEarnings,
                'total_referrals' => $totalReferrals,
                'recent_commissions' => $recentCommissions,
            ]
        ]);
    }
    
    /**
     * Generate or retrieve a referral link for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateReferralLink(Request $request)
    {
        $user = auth()->user();
        
        // Check if user already has a referral code
        $referral = AffiliateReferral::where('user_id', $user->id)->first();
        
        if (!$referral) {
            // Create new referral code
            $referralCode = $this->generateReferralCode($user);
            
            $referral = AffiliateReferral::create([
                'user_id' => $user->id,
                'referral_code' => $referralCode,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'referral_link' => config('app.url') . '/register?ref=' . $referral->referral_code,
                'referral_code' => $referral->referral_code,
            ]
        ]);
    }
    
    /**
     * Get the user's commission history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommissions(Request $request)
    {
        $commissions = AffiliateCommission::where('affiliate_id', auth()->id())
            ->with('referredUser:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => $commissions
        ]);
    }
    
    /**
     * Request a payout for earned commissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestPayout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100',
            'payout_method' => 'required|string|in:bank_transfer,paystack,paypal',
            'payout_details' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = auth()->user();
        
        // Calculate available earnings
        $availableEarnings = AffiliateCommission::where('affiliate_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');
            
        if ($request->amount > $availableEarnings) {
            return response()->json([
                'success' => false,
                'message' => 'Requested amount exceeds available earnings'
            ], 400);
        }
        
        // Create payout request
        $payoutRequest = AffiliatePayoutRequest::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'payout_method' => $request->payout_method,
            'payout_details' => $request->payout_details,
            'status' => 'pending',
            'reference' => 'PAYOUT-' . strtoupper(Str::random(10)),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Payout request submitted successfully',
            'data' => $payoutRequest
        ]);
    }
    
    /**
     * Get the payout history for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPayoutHistory(Request $request)
    {
        $payouts = AffiliatePayoutRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => $payouts
        ]);
    }
    
    /**
     * Generate a unique referral code for a user.
     *
     * @param  \App\Models\User  $user
     * @return string
     */
    private function generateReferralCode($user)
    {
        $baseCode = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $user->name));
        $uniqueCode = $baseCode . Str::random(4);
        
        // Check if code already exists
        while (AffiliateReferral::where('referral_code', $uniqueCode)->exists()) {
            $uniqueCode = $baseCode . Str::random(4);
        }
        
        return $uniqueCode;
    }
}
