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
            // Use getAvailableSmsCredits to include shared credits from team owners
            'smsBalance' => $user->getAvailableSmsCredits(),
            // Use getAvailableUssdCredits to include shared credits from team owners
            'ussdBalance' => $user->getAvailableUssdCredits(),
            // Use getAvailableContacts to include contacts shared from team owners
            'activeContacts' => $user->getAvailableContacts()->count(),
            'balance' => $user->formatAmount($user->wallet_balance ?? 0),
            'recentActivities' => $recentSmsActivities
        ]);
    }
}