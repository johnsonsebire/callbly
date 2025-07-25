<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactCenterCall;
use App\Models\Order;
use App\Models\SmsCampaign;
use App\Models\UssdService;
use App\Models\VirtualNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard overview data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get real account balance
        $accountBalance = [
            'sms_credits' => $user->sms_credits,
            'call_credits' => $user->call_credits,
            'ussd_credits' => $user->ussd_credits,
            'wallet_balance' => $user->wallet_balance ?? 0,
        ];
        
        // Get SMS statistics
        $smsStats = $this->getSmsStatistics($user->id);
        
        // Get USSD statistics
        $ussdStats = $this->getUssdStatistics($user->id);
        
        // Get contact center statistics
        $contactCenterStats = $this->getContactCenterStatistics($user->id);
        
        // Get virtual number statistics
        $virtualNumberStats = $this->getVirtualNumberStatistics($user->id);
        
        // Get recent transactions
        $recentTransactions = \App\Models\Order::where('user_id', $user->id)
            ->with(['servicePlan:id,name'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'reference_id' => $order->reference_id,
                    'amount' => $order->amount,
                    'status' => $order->status,
                    'service_plan' => $order->servicePlan ? $order->servicePlan->name : 'N/A',
                    'date' => $order->created_at->format('Y-m-d H:i'),
                ];
            });
        
        // Get usage statistics for mobile dashboard
        $usageStats = [
            'sms_sent_today' => $smsStats['sent_today'] ?? 0,
            'sms_sent_this_month' => $smsStats['sent_this_month'] ?? 0,
            'total_contacts' => $user->getAvailableContacts()->count(),
            'active_campaigns' => $smsStats['active_campaigns'] ?? 0,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'account_balance' => $accountBalance,
                'sms_statistics' => $smsStats,
                'ussd_statistics' => $ussdStats,
                'contact_center_statistics' => $contactCenterStats,
                'virtual_number_statistics' => $virtualNumberStats,
                'recent_transactions' => $recentTransactions,
                'usage_statistics' => $usageStats,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'currency' => [
                        'code' => $user->currency->code ?? 'GHS',
                        'symbol' => $user->currency->symbol ?? '₵',
                    ],
                ],
            ]
        ]);
    }
    
    /**
     * Get SMS statistics for a user.
     *
     * @param  int  $userId
     * @return array
     */
    private function getSmsStatistics($userId)
    {
        // Total sent messages (recipients count)
        $totalSent = SmsCampaign::where('user_id', $userId)->sum('recipients_count');
        
        // Messages sent today
        $sentToday = SmsCampaign::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('recipients_count');
            
        // Messages sent this month
        $sentThisMonth = SmsCampaign::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('recipients_count');
        
        // Messages sent in last 30 days
        $last30Days = SmsCampaign::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('recipients_count');
            
        // Active campaigns count (pending and processing)
        $activeCampaigns = SmsCampaign::where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing'])
            ->count();
            
        // Delivery rate
        $deliveryStats = SmsCampaign::where('user_id', $userId)
            ->selectRaw('SUM(recipients_count) as sent, SUM(delivered_count) as delivered')
            ->first();
            
        $deliveryRate = ($deliveryStats && $deliveryStats->sent > 0) 
            ? round(($deliveryStats->delivered / $deliveryStats->sent) * 100, 2)
            : 0;
            
        // Recent campaigns
        $recentCampaigns = SmsCampaign::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['id', 'name', 'recipients_count', 'delivered_count', 'created_at'])
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'sent' => $campaign->recipients_count,
                    'delivered' => $campaign->delivered_count,
                    'date' => $campaign->created_at->format('Y-m-d'),
                ];
            });
            
        return [
            'total_sent' => $totalSent,
            'sent_today' => $sentToday,
            'sent_this_month' => $sentThisMonth,
            'last_30_days' => $last30Days,
            'active_campaigns' => $activeCampaigns,
            'delivery_rate' => $deliveryRate,
            'recent_campaigns' => $recentCampaigns,
        ];
    }
    
    /**
     * Get USSD statistics for a user.
     *
     * @param  int  $userId
     * @return array
     */
    private function getUssdStatistics($userId)
    {
        // Total active services
        $activeServices = UssdService::where('user_id', $userId)
            ->where('status', 'active')
            ->count();
            
        // Total monthly requests (since total_sessions column doesn't exist)
        $totalMonthlyRequests = UssdService::where('user_id', $userId)
            ->sum('monthly_requests');
            
        // Total services count
        $totalServices = UssdService::where('user_id', $userId)->count();
        
        return [
            'active_services' => $activeServices,
            'total_services' => $totalServices,
            'monthly_requests' => $totalMonthlyRequests,
            'total_sessions' => $totalMonthlyRequests, // Alias for compatibility
            'last_30_days_sessions' => 0, // Default to 0 since no sessions table exists yet
        ];
    }
    
    /**
     * Get contact center statistics for a user.
     *
     * @param  int  $userId
     * @return array
     */
    private function getContactCenterStatistics($userId)
    {
        // Total calls
        $totalCalls = ContactCenterCall::where('user_id', $userId)->count();
        
        // Calls in last 30 days
        $last30DaysCalls = ContactCenterCall::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
            
        // Average call duration
        $avgDuration = ContactCenterCall::where('user_id', $userId)
            ->where('status', 'completed')
            ->avg('duration');
            
        // Format as minutes and seconds
        $avgDurationFormatted = $avgDuration ? floor($avgDuration / 60) . 'm ' . ($avgDuration % 60) . 's' : '0m 0s';
        
        return [
            'total_calls' => $totalCalls,
            'last_30_days_calls' => $last30DaysCalls,
            'avg_duration' => $avgDurationFormatted,
        ];
    }
    
    /**
     * Get virtual number statistics for a user.
     *
     * @param  int  $userId
     * @return array
     */
    private function getVirtualNumberStatistics($userId)
    {
        // Total active numbers
        $activeNumbers = VirtualNumber::where('user_id', $userId)
            ->where('status', 'active')
            ->count();
            
        // Numbers expiring soon (next 30 days)
        $expiringNumbers = VirtualNumber::where('user_id', $userId)
            ->where('status', 'active')
            ->where('expires_at', '<=', now()->addDays(30))
            ->where('expires_at', '>=', now())
            ->count();
            
        // Recent numbers
        $recentNumbers = VirtualNumber::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['id', 'number', 'status', 'expires_at'])
            ->map(function ($number) {
                return [
                    'id' => $number->id,
                    'number' => $number->number,
                    'status' => $number->status,
                    'expires_at' => $number->expires_at ? $number->expires_at->format('Y-m-d') : null,
                ];
            });
        
        return [
            'active_numbers' => $activeNumbers,
            'expiring_numbers' => $expiringNumbers,
            'recent_numbers' => $recentNumbers,
        ];
    }
}
