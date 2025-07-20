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
        // Total sent messages
        $totalSent = SmsCampaign::where('user_id', $userId)->sum('total_sent');
        
        // Messages sent today
        $sentToday = SmsCampaign::where('user_id', $userId)
            ->whereDate('created_at', today())
            ->sum('total_sent');
            
        // Messages sent this month
        $sentThisMonth = SmsCampaign::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_sent');
        
        // Messages sent in last 30 days
        $last30Days = SmsCampaign::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('total_sent');
            
        // Active campaigns count
        $activeCampaigns = SmsCampaign::where('user_id', $userId)
            ->where('status', 'active')
            ->count();
            
        // Delivery rate
        $deliveryStats = SmsCampaign::where('user_id', $userId)
            ->selectRaw('SUM(total_sent) as sent, SUM(total_delivered) as delivered')
            ->first();
            
        $deliveryRate = ($deliveryStats && $deliveryStats->sent > 0) 
            ? round(($deliveryStats->delivered / $deliveryStats->sent) * 100, 2)
            : 0;
            
        // Recent campaigns
        $recentCampaigns = SmsCampaign::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get(['id', 'name', 'total_sent', 'total_delivered', 'created_at'])
            ->map(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'sent' => $campaign->total_sent,
                    'delivered' => $campaign->total_delivered,
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
            
        // Total sessions
        $totalSessions = UssdService::where('user_id', $userId)
            ->sum('total_sessions');
            
        // Sessions in last 30 days
        $last30DaysSessionsQuery = DB::table('ussd_sessions')
            ->join('ussd_services', 'ussd_sessions.ussd_service_id', '=', 'ussd_services.id')
            ->where('ussd_services.user_id', $userId)
            ->where('ussd_sessions.created_at', '>=', now()->subDays(30))
            ->count();
        
        return [
            'active_services' => $activeServices,
            'total_sessions' => $totalSessions,
            'last_30_days_sessions' => $last30DaysSessionsQuery,
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
