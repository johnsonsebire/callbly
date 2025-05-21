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
        
        return view('dashboard.dashboard', [
            'smsBalance' => $user->sms_credits ?? 0,
            'ussdBalance' => $user->ussd_credits ?? 0,
            // 'activeContacts' => $user->contacts()->count() ?? 0,
            'balance' => $user->formatAmount($user->wallet_balance ?? 0),
            // 'recentActivities' => $user->activities()->latest()->take(5)->get()
        ]);
    }
}