<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;

class SetTeamContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Set the team context for permissions
            if ($user->current_team_id) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_team_id);
            } else {
                // If no current team is set, use the first team the user belongs to
                $firstTeam = $user->ownedTeams()->first() ?: $user->teams()->first();
                if ($firstTeam) {
                    app(PermissionRegistrar::class)->setPermissionsTeamId($firstTeam->id);
                    
                    // Also update the user's current_team_id if it's not set
                    if (!$user->current_team_id) {
                        $user->current_team_id = $firstTeam->id;
                        $user->save();
                    }
                }
            }
        }

        return $next($request);
    }
}
