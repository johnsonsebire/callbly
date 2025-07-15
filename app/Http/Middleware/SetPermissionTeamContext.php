<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetPermissionTeamContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only set team context for authenticated users
        if (auth()->check()) {
            $user = auth()->user();
            
            // Set the permission team context to the user's current team
            if ($user->current_team_id) {
                app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
            }
        }

        return $next($request);
    }
}
