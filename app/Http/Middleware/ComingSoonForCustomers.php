<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ComingSoonForCustomers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        $user = auth()->user();
        
        // Allow access for Super Admins and Staff
        if ($user && ($user->isSuperAdmin() || $user->isStaff())) {
            return $next($request);
        }
        
        // For customers, show coming soon page
        return response()->view('coming-soon.index', [
            'feature' => $feature ?: 'This Feature',
            'title' => $this->getFeatureTitle($feature),
            'description' => $this->getFeatureDescription($feature),
            'icon' => $this->getFeatureIcon($feature)
        ]);
    }
    
    /**
     * Get feature title based on feature name
     */
    private function getFeatureTitle(?string $feature): string
    {
        return match($feature) {
            'virtual-numbers' => 'Virtual Numbers',
            'contact-center' => 'Contact Center',
            'ussd' => 'USSD Services',
            default => 'Coming Soon'
        };
    }
    
    /**
     * Get feature description based on feature name
     */
    private function getFeatureDescription(?string $feature): string
    {
        return match($feature) {
            'virtual-numbers' => 'Get dedicated virtual phone numbers for your business. Receive calls and SMS, set up call forwarding, and manage multiple numbers from one dashboard.',
            'contact-center' => 'Transform your customer service with our advanced contact center solution. Manage multiple agents, call routing, real-time dashboards, and comprehensive analytics.',
            'ussd' => 'Create interactive USSD services for your customers. Build custom menus, collect data, and provide instant responses through USSD codes.',
            default => 'This exciting feature is coming soon to Callbly. Stay tuned for updates!'
        };
    }
    
    /**
     * Get feature icon based on feature name
     */
    private function getFeatureIcon(?string $feature): string
    {
        return match($feature) {
            'virtual-numbers' => 'ki-duotone ki-phone',
            'contact-center' => 'ki-duotone ki-support',
            'ussd' => 'ki-duotone ki-code',
            default => 'ki-duotone ki-timer'
        };
    }
}
