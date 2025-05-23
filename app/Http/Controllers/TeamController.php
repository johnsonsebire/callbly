<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /**
     * Display a listing of the teams.
     */
    public function index()
    {
        $user = Auth::user();
        $teams = $user->allTeams();
        
        return view('teams.index', [
            'user' => $user,
            'teams' => $teams,
        ]);
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
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        
        $team = $user->ownedTeams()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'personal_team' => false,
            'owner_id' => $user->id,
        ]);
        
        // Set the newly created team as the user's current team
        $user->current_team_id = $team->id;
        $user->save();
        
        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team)
    {
        if (! Gate::allows('view-team', $team)) {
            abort(403);
        }
        
        return view('teams.show', [
            'team' => $team,
            'members' => $team->users,
            'invitations' => $team->invitations,
            'availableSenderNames' => $team->senderNames,
        ]);
    }

    /**
     * Show the form for editing the specified team.
     */
    public function edit(Team $team)
    {
        if (! Gate::allows('update-team', $team)) {
            abort(403);
        }
        
        return view('teams.edit', [
            'team' => $team,
        ]);
    }

    /**
     * Update the specified team in storage.
     */
    public function update(Request $request, Team $team)
    {
        if (! Gate::allows('update-team', $team)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'share_sms_credits' => ['sometimes', 'boolean'],
            'share_contacts' => ['sometimes', 'boolean'],
            'share_sender_names' => ['sometimes', 'boolean'],
        ]);

        $team->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Team settings updated successfully',
                'team' => $team->fresh()
            ]);
        }

        return back()->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Team $team)
    {
        if (! Gate::allows('delete-team', $team)) {
            abort(403);
        }
        
        $user = Auth::user();
        
        // Can't delete a personal team
        if ($team->personal_team) {
            return back()->withErrors(['error' => 'You cannot delete your personal team.']);
        }
        
        // If the team is the user's current team, set their current team to null
        if ($user->current_team_id === $team->id) {
            $user->current_team_id = null;
            $user->save();
        }
        
        $team->delete();
        
        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }
    
    /**
     * Update the team membership for the given user.
     */
    public function updateMember(Request $request, Team $team, User $user)
    {
        if (! Gate::allows('update-team-member', $team)) {
            abort(403);
        }
        
        // Team owner can't have their role changed
        if ($team->isOwner($user)) {
            return back()->withErrors(['error' => 'The team owner\'s role cannot be changed.']);
        }
        
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,member'],
        ]);
        
        $team->users()->updateExistingPivot($user->id, [
            'role' => $validated['role'],
        ]);
        
        return back()->with('success', 'Team member role updated successfully.');
    }
    
    /**
     * Remove the given user from the given team.
     */
    public function removeMember(Request $request, Team $team, User $user)
    {
        if (! Gate::allows('remove-team-member', $team)) {
            abort(403);
        }
        
        // Team owner can't be removed
        if ($team->isOwner($user)) {
            return back()->withErrors(['error' => 'The team owner cannot be removed.']);
        }
        
        // Can't remove yourself
        if ($request->user()->id === $user->id) {
            return back()->withErrors(['error' => 'You cannot remove yourself from the team.']);
        }
        
        $team->users()->detach($user->id);
        
        return back()->with('success', 'Team member removed successfully.');
    }
    
    /**
     * Leave a team.
     */
    public function leave(Team $team)
    {
        $user = Auth::user();
        
        // Team owner can't leave
        if ($team->isOwner($user)) {
            return back()->withErrors(['error' => 'Team owners cannot leave their own teams.']);
        }
        
        // If this is the current team, reset the current team ID
        if ($user->current_team_id === $team->id) {
            $user->current_team_id = null;
            $user->save();
        }
        
        $team->users()->detach($user->id);
        
        return redirect()->route('teams.index')
            ->with('success', 'You have left the team successfully.');
    }
    
    /**
     * Switch the user's current team.
     */
    public function switchTeam(Team $team)
    {
        $user = Auth::user();
        
        // Check if the user belongs to the team
        if (! $user->belongsToTeam($team)) {
            abort(403);
        }
        
        $user->current_team_id = $team->id;
        $user->save();
        
        return redirect()->route('teams.show', $team)
            ->with('success', 'Current team switched successfully.');
    }
}
