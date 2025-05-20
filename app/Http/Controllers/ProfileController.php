<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\User;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected CurrencyService $currencyService;
    
    /**
     * Create a new controller instance.
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    
    /**
     * Show the user profile page.
     */
    public function show(): View
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }
    
    /**
     * Edit the user profile page.
     */
    public function edit(): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * Update the user profile.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show the currency settings page.
     */
    public function showCurrencySettings(): View
    {
        $user = Auth::user();
        $currencies = Currency::where('is_active', true)->get();
        $currentCurrency = $user->currency ?? Currency::getDefaultCurrency();
        
        return view('profile.currency', compact('currencies', 'currentCurrency'));
    }
    
    /**
     * Update the user's currency preferences.
     */
    public function updateCurrency(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currency_id' => 'required|exists:currencies,id'
        ]);
        
        $user = Auth::user();
        $user->currency_id = $validated['currency_id'];
        $user->save();
        
        return redirect()->route('settings.currency')
            ->with('success', 'Currency preferences updated successfully.');
    }
}
