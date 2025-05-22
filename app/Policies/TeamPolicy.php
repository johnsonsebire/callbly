<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) || 
               ($user->belongsToTeam($team) && $user->teamRole($team) === 'admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can invite others to the team.
     */
    public function inviteToTeam(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) || 
               ($user->belongsToTeam($team) && $user->teamRole($team) === 'admin');
    }
    
    /**
     * Determine whether the user can update team member roles.
     */
    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team);
    }
    
    /**
     * Determine whether the user can remove team members.
     */
    public function removeTeamMember(User $user, Team $team): bool
    {
        return $user->ownsTeam($team) || 
               ($user->belongsToTeam($team) && $user->teamRole($team) === 'admin');
    }
    
    /**
     * Determine whether the user can view the team's resources (SMS credits, contacts, etc.).
     */
    public function viewTeamResources(User $user, Team $team): bool
    {
        return $user->belongsToTeam($team);
    }
    
    /**
     * Determine whether the user can use the team's resources (SMS credits, contacts, etc.).
     */
    public function useTeamResources(User $user, Team $team, string $resourceType): bool
    {
        if (!$user->belongsToTeam($team)) {
            return false;
        }
        
        // Team owners and admins can use all resources
        if ($user->ownsTeam($team) || $user->teamRole($team) === 'admin') {
            return true;
        }
        
        // Regular members can use resources based on team settings
        switch ($resourceType) {
            case 'sms_credits':
                return $team->share_sms_credits;
            case 'contacts':
                return $team->share_contacts;
            case 'sender_names':
                return $team->share_sender_names;
            default:
                return false;
        }
    }
}
