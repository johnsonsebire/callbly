<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\SenderName;
use App\Models\Contact;
use App\Models\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the teams.
     */
    public function index()
    {
        $user = auth()->user();
        
        $ownedTeams = $user->ownedTeams;
        $teams = $user->teams;

        return view('teams.index', compact('ownedTeams', 'teams', 'user'));
    }

    /**
     * Show the form for creating a new team.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created team in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string']
        ]);

        $team = DB::transaction(function () use ($validated, $request) {
            $team = Team::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'owner_id' => auth()->id(),
                'personal_team' => false
            ]);

            $team->users()->attach(
                auth()->id(),
                ['role' => 'owner']
            );

            return $team;
        });

        return redirect()->route('teams.show', $team)
                        ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team)
    {
        // Access check using User model's belongsToTeam method
        if (!auth()->user()->belongsToTeam($team)) {
            abort(403, 'You do not have access to this team.');
        }

        $members = $team->users()
                       ->withPivot('role')
                       ->orderBy('name')
                       ->get();

        $invitations = $team->invitations;
        
        $availableSenderNames = SenderName::query()
            ->where('user_id', $team->owner_id)
            ->approved()
            ->get();

        return view('teams.show', compact('team', 'members', 'invitations', 'availableSenderNames'));
    }

    /**
     * Update the specified team in storage.
     */
    public function update(Request $request, Team $team)
    {
        // Check if user can update team using ownsTeam method
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can update team settings.');
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'share_sms_credits' => ['sometimes', 'boolean'],
            'share_contacts' => ['sometimes', 'boolean'],
            'share_sender_names' => ['sometimes', 'boolean']
        ]);

        DB::transaction(function () use ($team, $validated) {
            $team->update($validated);

            if (isset($validated['share_sender_names'])) {
                if ($validated['share_sender_names']) {
                    $senderNames = SenderName::where('user_id', $team->owner_id)
                                           ->approved()
                                           ->get();
                    $team->syncResources($senderNames, 'sender_name');
                } else {
                    $team->teamResources()
                         ->where('resource_type', 'sender_name')
                         ->delete();
                }
            }

            if (isset($validated['share_contacts'])) {
                if ($validated['share_contacts']) {
                    $contacts = Contact::where('user_id', $team->owner_id)->get();
                    $team->syncResources($contacts, 'contact');
                } else {
                    $team->teamResources()
                         ->where('resource_type', 'contact')
                         ->delete();
                }
            }
        });

        return back()->with('success', 'Team settings updated successfully.');
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Team $team)
    {
        // Check if user can delete team using ownsTeam method
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can delete teams.');
        }

        if ($team->personal_team) {
            return back()->with('error', 'Cannot delete personal team.');
        }

        DB::transaction(function () use ($team) {
            $team->teamResources()->delete();
            $team->users()->detach();
            $team->invitations()->delete();
            $team->delete();
        });

        return redirect()->route('teams.index')
                        ->with('success', 'Team deleted successfully.');
    }

    /**
     * Update team member role.
     */
    public function updateMember(Request $request, Team $team, User $user)
    {
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can update member roles.');
        }

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:' . implode(',', TeamUser::roles())]
        ]);

        if ($user->id === $team->owner_id) {
            return back()->with('error', 'Cannot change the role of the team owner.');
        }

        $team->users()->updateExistingPivot($user->id, [
            'role' => $validated['role']
        ]);

        return back()->with('success', 'Team member role updated successfully.');
    }

    /**
     * Remove team member.
     */
    public function removeMember(Team $team, User $user)
    {
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can remove team members.');
        }

        if ($user->id === $team->owner_id) {
            return back()->with('error', 'Cannot remove the team owner.');
        }

        $team->users()->detach($user->id);

        return back()->with('success', 'Team member removed successfully.');
    }

    /**
     * Leave team.
     */
    public function leave(Team $team)
    {
        $user = auth()->user();

        if ($user->id === $team->owner_id) {
            return back()->with('error', 'Team owners cannot leave their own team.');
        }

        $team->users()->detach($user->id);

        return redirect()->route('teams.index')
                        ->with('success', 'You have left the team successfully.');
    }

    /**
     * Switch to team.
     */
    public function switchTeam(Team $team)
    {
        $user = auth()->user();

        if (!$user->belongsToTeam($team)) {
            abort(403, 'You do not have access to this team.');
        }

        $user->current_team_id = $team->id;
        $user->save();

        return back()->with('success', 'Current team switched successfully.');
    }
}
