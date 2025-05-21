<?php

namespace App\Http\Controllers;

use App\Models\UssdService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UssdController extends Controller
{
    /**
     * Show the USSD dashboard.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        $servicesCount = UssdService::where('user_id', $user->id)->count();
        
        return view('ussd.dashboard', compact('servicesCount'));
    }

    /**
     * Show the USSD services page.
     */
    public function services(): View
    {
        $user = auth()->user();
        $services = UssdService::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('ussd.services', compact('services'));
    }

    /**
     * Show the USSD analytics page.
     */
    public function analytics(): View
    {
        $user = auth()->user();
        $services = UssdService::where('user_id', $user->id)->get();
        
        // Get analytics data for all services
        $analyticsData = collect($services)->map(function ($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'total_sessions' => $service->sessions_count,
                'active_users' => $service->active_users_count,
                'average_session_duration' => $service->average_session_duration
            ];
        });
        
        return view('ussd.analytics', compact('analyticsData'));
    }

    /**
     * Show the create USSD service page.
     */
    public function create(): View
    {
        return view('ussd.create');
    }

    /**
     * Store a new USSD service.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:20|unique:ussd_services,shortcode',
            'menu_structure' => 'required|json',
            'callback_url' => 'nullable|url'
        ]);

        $menuStructure = json_decode($validated['menu_structure'], true);
        
        $service = UssdService::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'shortcode' => $validated['shortcode'],
            'menu_structure' => $menuStructure,
            'status' => 'pending',
            'callback_url' => $validated['callback_url']
        ]);

        return redirect()->route('ussd.services')
            ->with('success', 'USSD service created successfully and pending approval');
    }

    /**
     * Show the edit USSD service page.
     */
    public function edit($id): View
    {
        $user = auth()->user();
        $service = UssdService::where('user_id', $user->id)
            ->findOrFail($id);
        
        return view('ussd.edit', compact('service'));
    }

    /**
     * Update the USSD service.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $service = UssdService::where('user_id', $user->id)
            ->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shortcode' => 'required|string|max:20|unique:ussd_services,shortcode,' . $id,
            'menu_structure' => 'required|json',
            'callback_url' => 'nullable|url'
        ]);

        $menuStructure = json_decode($validated['menu_structure'], true);
        
        $service->update([
            'name' => $validated['name'],
            'shortcode' => $validated['shortcode'],
            'menu_structure' => $menuStructure,
            'callback_url' => $validated['callback_url']
        ]);

        return redirect()->route('ussd.services')
            ->with('success', 'USSD service updated successfully');
    }

    /**
     * Delete the USSD service.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $service = UssdService::where('user_id', $user->id)
            ->findOrFail($id);
        
        $service->delete();

        return redirect()->route('ussd.services')
            ->with('success', 'USSD service deleted successfully');
    }
}