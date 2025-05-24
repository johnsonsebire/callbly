<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\SenderName;
use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TeamResourceService
{
    /**
     * Get all available SMS credits for a user including shared team credits
     *
     * @param User $user
     * @return int
     */
    public function getAvailableSmsCredits(User $user): int
    {
        $total = $user->sms_credits;

        if ($this->userHasNoTeams($user)) {
            return $total;
        }

        // Add credits from teams where SMS credits are shared
        foreach ($user->teams as $team) {
            if ($team->share_sms_credits && !$user->ownsTeam($team)) {
                $owner = $team->owner;
                if ($owner) {
                    $total += $owner->sms_credits;
                }
            }
        }

        return $total;
    }

    /**
     * Deduct SMS credits with proper handling of shared team credits
     * 
     * @param User $user The user sending the message
     * @param int $creditsNeeded Total credits needed for the operation
     * @return array Details of the deduction operation
     */
    public function deductSharedSmsCredits(User $user, int $creditsNeeded): array
    {
        // Initialize result
        $result = [
            'success' => false,
            'personal_credits_used' => 0,
            'team_credits_used' => 0,
            'team_id' => null,
            'team_owner_id' => null,
            'message' => ''
        ];

        // Check if user has enough credits (including team credits)
        $availableCredits = $this->getAvailableSmsCredits($user);
        if ($availableCredits < $creditsNeeded) {
            $result['message'] = "Insufficient credits. Need {$creditsNeeded}, but only have {$availableCredits}.";
            return $result;
        }

        try {
            DB::beginTransaction();

            // First use personal credits
            $personalCreditsToUse = min($user->sms_credits, $creditsNeeded);
            if ($personalCreditsToUse > 0) {
                $user->sms_credits -= $personalCreditsToUse;
                $user->save();
                $result['personal_credits_used'] = $personalCreditsToUse;
            }

            // If we need more credits than personal credits available, use team credits
            $remainingCreditsNeeded = $creditsNeeded - $personalCreditsToUse;
            if ($remainingCreditsNeeded > 0) {
                // Find a team that shares credits with this user
                $team = $user->teams()
                    ->where('share_sms_credits', true)
                    ->first();

                if ($team && $team->owner) {
                    $teamOwner = $team->owner;
                    
                    // Ensure team owner has enough credits
                    if ($teamOwner->sms_credits >= $remainingCreditsNeeded) {
                        $teamOwner->sms_credits -= $remainingCreditsNeeded;
                        $teamOwner->save();
                        
                        $result['team_credits_used'] = $remainingCreditsNeeded;
                        $result['team_id'] = $team->id;
                        $result['team_owner_id'] = $teamOwner->id;
                    } else {
                        // If team owner doesn't have enough credits, roll back and return error
                        DB::rollBack();
                        $result['message'] = "Team owner doesn't have enough credits for the remaining {$remainingCreditsNeeded} credits needed.";
                        return $result;
                    }
                } else {
                    // No team with shared credits found
                    DB::rollBack();
                    $result['message'] = "No team with shared credits found for the remaining {$remainingCreditsNeeded} credits needed.";
                    return $result;
                }
            }

            DB::commit();
            $result['success'] = true;
            $result['message'] = "Successfully deducted credits.";
            
            // Log the credit deduction
            Log::info('SMS credits deducted', [
                'user_id' => $user->id,
                'personal_credits_used' => $result['personal_credits_used'],
                'team_credits_used' => $result['team_credits_used'],
                'team_id' => $result['team_id'],
                'team_owner_id' => $result['team_owner_id']
            ]);
            
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deducting SMS credits', [
                'user_id' => $user->id,
                'credits_needed' => $creditsNeeded,
                'error' => $e->getMessage()
            ]);
            
            $result['message'] = "Error deducting credits: " . $e->getMessage();
            return $result;
        }
    }

    /**
     * Get all available USSD credits for a user including shared team credits
     *
     * @param User $user
     * @return int
     */
    public function getAvailableUssdCredits(User $user): int
    {
        $total = $user->ussd_credits;

        if ($this->userHasNoTeams($user)) {
            return $total;
        }

        // Add credits from teams where resources are shared (assuming same sharing setting)
        foreach ($user->teams as $team) {
            if ($team->share_sms_credits && !$user->ownsTeam($team)) {
                // Using the same sharing flag as SMS credits until a specific USSD sharing flag is added
                $owner = $team->owner;
                if ($owner) {
                    $total += $owner->ussd_credits;
                }
            }
        }

        return $total;
    }

    /**
     * Get all available contacts for a user including shared team contacts
     *
     * @param User $user
     * @return Collection
     */
    public function getAvailableContacts(User $user): Collection
    {
        // Start with the user's own contacts
        $contacts = Contact::where('user_id', $user->id)->get();
        
        // Get contacts shared by teams
        $teams = $user->memberOfTeams()->where('share_contacts', true)->get();
        
        foreach ($teams as $team) {
            $ownerContacts = Contact::where('user_id', $team->owner_id)->get();
            $contacts = $contacts->concat($ownerContacts);
        }
        
        return $contacts->unique('id');
    }
    
    /**
     * Get all available contact groups for a user including shared team contact groups
     *
     * @param User $user
     * @return Collection
     */
    public function getAvailableContactGroups(User $user): Collection
    {
        // Start with the user's own contact groups
        $contactGroups = ContactGroup::where('user_id', $user->id)->get();
        
        // Get contact groups shared by teams
        $teams = $user->memberOfTeams()->where('share_contact_groups', true)->get();
        
        foreach ($teams as $team) {
            $ownerContactGroups = ContactGroup::where('user_id', $team->owner_id)->get();
            $contactGroups = $contactGroups->concat($ownerContactGroups);
        }
        
        return $contactGroups->unique('id');
    }
    
    /**
     * Get all available sender names for a user including shared team sender names
     *
     * @param User $user
     * @return Collection
     */
    public function getAvailableSenderNames(User $user): Collection
    {
        // Start with the user's own approved sender names
        $senderNames = SenderName::where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();
        
        // Get sender names shared by teams
        $teams = $user->memberOfTeams()->where('share_sender_names', true)->get();
        
        foreach ($teams as $team) {
            $ownerSenderNames = SenderName::where('user_id', $team->owner_id)
                ->where('status', 'approved')
                ->get();
            $senderNames = $senderNames->concat($ownerSenderNames);
        }
        
        return $senderNames->unique('id');
    }

    /**
     * Check if a user can use a specific team resource type
     *
     * @param User $user
     * @param string $resourceType sms_credits|contacts|sender_names|contact_groups
     * @return bool
     */
    public function canUseTeamResource(User $user, string $resourceType): bool
    {
        if ($this->userHasNoTeams($user)) {
            return false;
        }

        foreach ($user->teams as $team) {
            if ($user->ownsTeam($team)) {
                continue; // Skip teams owned by the user
            }

            switch ($resourceType) {
                case 'sms_credits':
                    if ($team->share_sms_credits) {
                        return true;
                    }
                    break;
                case 'contacts':
                    if ($team->share_contacts) {
                        return true;
                    }
                    break;
                case 'sender_names':
                    if ($team->share_sender_names) {
                        return true;
                    }
                    break;
                case 'contact_groups':
                    if ($team->share_contact_groups) {
                        return true;
                    }
                    break;
            }
        }

        return false;
    }

    /**
     * Get a team owner's resources that the current user has access to
     *
     * @param User $user
     * @param Team $team
     * @param string $resourceType
     * @return mixed
     */
    public function getTeamOwnerResources(User $user, Team $team, string $resourceType)
    {
        if ($user->ownsTeam($team)) {
            return null; // User is the owner, no need to get resources
        }

        $owner = $team->owner;
        if (!$owner) {
            return null;
        }

        switch ($resourceType) {
            case 'sms_credits':
                return $team->share_sms_credits ? $owner->sms_credits : 0;
            case 'contacts':
                return $team->share_contacts ? Contact::where('user_id', $owner->id)->get() : collect();
            case 'sender_names':
                return $team->share_sender_names ? 
                    SenderName::where('user_id', $owner->id)->approved()->get() : 
                    collect();
            case 'contact_groups':
                return $team->share_contact_groups ?
                    ContactGroup::where('user_id', $owner->id)->get() :
                    collect();
            default:
                return null;
        }
    }

    /**
     * Check if a user belongs to any teams
     *
     * @param User $user
     * @return bool
     */
    private function userHasNoTeams(User $user): bool
    {
        return $user->teams->isEmpty();
    }
}