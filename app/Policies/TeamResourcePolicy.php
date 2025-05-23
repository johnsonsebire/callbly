<?php

namespace App\Policies;

use App\Models\TeamResource;
use App\Models\User;

class TeamResourcePolicy
{
    public function viewAny(User $user, $team)
    {
        return $user->belongsToTeam($team);
    }

    public function view(User $user, TeamResource $resource)
    {
        return $user->belongsToTeam($resource->team) && 
               ($resource->is_shared || $user->ownsTeam($resource->team));
    }

    public function create(User $user, $team)
    {
        return $user->ownsTeam($team) || 
               $user->hasTeamRole($team, 'admin');
    }

    public function update(User $user, TeamResource $resource)
    {
        return $user->ownsTeam($resource->team) || 
               $user->hasTeamRole($resource->team, 'admin');
    }

    public function delete(User $user, TeamResource $resource)
    {
        return $user->ownsTeam($resource->team) || 
               $user->hasTeamRole($resource->team, 'admin');
    }

    public function share(User $user, TeamResource $resource)
    {
        return $user->ownsTeam($resource->team) || 
               $user->hasTeamRole($resource->team, 'admin');
    }
}