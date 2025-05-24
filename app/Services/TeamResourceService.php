<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\SenderName;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;

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
        $personalContacts = $user->contacts;

        if ($this->userHasNoTeams($user)) {
            return $personalContacts;
        }

        $sharedContacts = collect();

        // Add contacts from teams where contacts are shared
        foreach ($user->teams as $team) {
            if ($team->share_contacts && !$user->ownsTeam($team)) {
                $owner = $team->owner;
                if ($owner) {
                    $ownerContacts = Contact::where('user_id', $owner->id)->get();
                    $sharedContacts = $sharedContacts->merge($ownerContacts);
                }
            }
        }

        return $personalContacts->merge($sharedContacts)->unique('id');
    }

    /**
     * Get all available sender names for a user including shared team sender names
     *
     * @param User $user
     * @return Collection
     */
    public function getAvailableSenderNames(User $user): Collection
    {
        $personalSenderNames = $user->senderNames;

        if ($this->userHasNoTeams($user)) {
            return $personalSenderNames;
        }

        $sharedSenderNames = collect();

        // Add sender names from teams where sender names are shared
        foreach ($user->teams as $team) {
            if ($team->share_sender_names && !$user->ownsTeam($team)) {
                $owner = $team->owner;
                if ($owner) {
                    $ownerSenderNames = SenderName::where('user_id', $owner->id)
                        ->approved()
                        ->get();
                    $sharedSenderNames = $sharedSenderNames->merge($ownerSenderNames);
                }
            }
        }

        return $personalSenderNames->merge($sharedSenderNames)->unique('id');
    }

    /**
     * Check if a user can use a specific team resource type
     *
     * @param User $user
     * @param string $resourceType sms_credits|contacts|sender_names
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