<?php

namespace App\Http\Controllers;

use App\Services\Currency\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index(): View
    {
        $user = auth()->user();
        
        // Get recent SMS activities from recipients
        $recentSmsActivities = $user->smsCampaigns()
            ->with('recipients')
            ->get()
            ->flatMap(function ($campaign) {
                return $campaign->recipients->map(function ($recipient) use ($campaign) {
                    return (object)[
                        'type' => 'sms',
                        'recipient' => $recipient->phone_number,
                        'status' => $recipient->status,
                        'created_at' => $recipient->created_at,
                        'campaign_id' => $campaign->id,
                        'message' => $campaign->message
                    ];
                });
            })
            ->sortByDesc('created_at')
            ->take(5);
        
        return view('dashboard.dashboard', [
            'smsBalance' => $user->sms_credits ?? 0,
            'ussdBalance' => $user->ussd_credits ?? 0,
            'activeContacts' => $user->contacts()->count() ?? 0,
            'balance' => $user->formatAmount($user->wallet_balance ?? 0),
            'recentActivities' => $recentSmsActivities
        ]);
    }
}