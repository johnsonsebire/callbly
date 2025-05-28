<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMeetingSchedulingSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Check if user has an active meeting scheduling subscription
        $hasActiveSubscription = $user->meetingSchedulingSubscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();

        if (!$hasActiveSubscription) {
            return redirect()->route('meeting-scheduling.subscribe')
                ->with('warning', 'You need an active Meeting Scheduling subscription to access this feature.');
        }

        return $next($request);
    }
}