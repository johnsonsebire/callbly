<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\User;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Inertia\Inertia;

class ProfileController extends Controller
{
    protected CurrencyService $currencyService;
    
    /**
     * Create a new controller instance.
     */
    public function __construct(CurrencyService $currencyService)
    {
        // Removed middleware call since it's not available in Laravel 12
        // Middleware should be defined in routes or route groups instead
        $this->currencyService = $currencyService;
    }
    
    /**
     * Show the user profile page.
     * 
     * @return \Inertia\Response
     */
    public function show()
    {
        $user = auth()->user();
        return Inertia::render('Profile/Show', compact('user'));
    }
    
    /**
     * Update the user profile.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
        
        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show the currency settings page.
     * 
     * @return \Inertia\Response
     */
    public function showCurrencySettings()
    {
        return Inertia::render('Profile/Currency');
    }
    
    /**
     * Update the user's currency preferences.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
