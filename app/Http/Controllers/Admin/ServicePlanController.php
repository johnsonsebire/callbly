<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePlan;
use App\Services\Currency\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServicePlanController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    /**
     * Display a listing of the service plans.
     */
    public function index()
    {
        $plans = ServicePlan::orderBy('type')
            ->orderBy('price')
            ->paginate(15);

        return view('admin.service-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new service plan.
     */
    public function create()
    {
        return view('admin.service-plans.create');
    }

    /**
     * Store a newly created service plan in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:sms,voice,contact-center,virtual-number',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'required|integer|min:1',
            'units' => 'required|integer|min:0',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Convert price from user's currency to base currency (GHS)
        $user = auth()->user();
        $baseCurrencyPrice = $this->currencyService->convert(
            $request->price,
            $user->currency,
            'GHS'
        );

        ServicePlan::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'price' => $baseCurrencyPrice, // Store in base currency
            'validity_days' => $request->validity_days,
            'units' => $request->units,
            'features' => array_filter($request->features), // Remove empty features
            'is_popular' => $request->boolean('is_popular'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.service-plans.index')
            ->with('success', 'Service plan created successfully.');
    }

    /**
     * Display the specified service plan.
     */
    public function show(ServicePlan $servicePlan)
    {
        return view('admin.service-plans.show', compact('servicePlan'));
    }

    /**
     * Show the form for editing the specified service plan.
     */
    public function edit(ServicePlan $servicePlan)
    {
        $user = auth()->user();
        
        // Convert price from base currency to user's currency for editing
        $userCurrencyPrice = $this->currencyService->convert(
            $servicePlan->price,
            'GHS',
            $user->currency
        );
        
        $servicePlan->user_currency_price = $userCurrencyPrice;
        
        return view('admin.service-plans.edit', compact('servicePlan'));
    }

    /**
     * Update the specified service plan in storage.
     */
    public function update(Request $request, ServicePlan $servicePlan)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:sms,voice,contact-center,virtual-number',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'required|integer|min:1',
            'units' => 'required|integer|min:0',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Convert price from user's currency to base currency (GHS)
        $user = auth()->user();
        $baseCurrencyPrice = $this->currencyService->convert(
            $request->price,
            $user->currency,
            'GHS'
        );

        $servicePlan->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'price' => $baseCurrencyPrice, // Store in base currency
            'validity_days' => $request->validity_days,
            'units' => $request->units,
            'features' => array_filter($request->features), // Remove empty features
            'is_popular' => $request->boolean('is_popular'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.service-plans.index')
            ->with('success', 'Service plan updated successfully.');
    }

    /**
     * Remove the specified service plan from storage.
     */
    public function destroy(ServicePlan $servicePlan)
    {
        // Check if plan has active orders
        $activeOrders = $servicePlan->orders()
            ->where('status', 'completed')
            ->where('expires_at', '>', now())
            ->count();

        if ($activeOrders > 0) {
            return back()->with('error', 'Cannot delete service plan with active subscriptions.');
        }

        $servicePlan->delete();

        return redirect()->route('admin.service-plans.index')
            ->with('success', 'Service plan deleted successfully.');
    }

    /**
     * Toggle the active status of a service plan.
     */
    public function toggleStatus(ServicePlan $servicePlan)
    {
        $servicePlan->update(['is_active' => !$servicePlan->is_active]);

        $status = $servicePlan->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Service plan {$status} successfully.");
    }
}
