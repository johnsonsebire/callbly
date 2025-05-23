<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\SenderName;
use App\Models\Contact;
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

        return view('teams.index', compact('ownedTeams', 'teams'));
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
        if (!Gate::allows('view', $team)) {
            abort(403);
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
        if (!Gate::allows('update-team', $team)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'share_sms_credits' => ['sometimes', 'boolean'],
            'share_contacts' => ['sometimes', 'boolean'],
            'share_sender_names' => ['sometimes', 'boolean']
        ]);

        DB::transaction(function () use ($team, $validated) {
            // Update team settings
            $team->update($validated);

            // Handle sender names sharing
            if (isset($validated['share_sender_names'])) {
                if ($validated['share_sender_names']) {
                    // Share all owner's sender names with the team
                    $senderNames = SenderName::where('user_id', $team->owner_id)
                                           ->approved()
                                           ->get();
                    $team->syncResources($senderNames, 'sender_name');
                } else {
                    // Remove all shared sender names
                    $team->teamResources()
                         ->where('resource_type', 'sender_name')
                         ->delete();
                }
            }

            // Handle contacts sharing
            if (isset($validated['share_contacts'])) {
                if ($validated['share_contacts']) {
                    // Share all owner's contacts with the team
                    $contacts = Contact::where('user_id', $team->owner_id)->get();
                    $team->syncResources($contacts, 'contact');
                } else {
                    // Remove all shared contacts
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
        if (!Gate::allows('delete', $team)) {
            abort(403);
        }

        // Don't allow deleting personal teams
        if ($team->personal_team) {
            return back()->with('error', 'Cannot delete personal team.');
        }

        DB::transaction(function () use ($team) {
            // Remove all team resources
            $team->teamResources()->delete();
            
            // Remove all team memberships
            $team->users()->detach();
            
            // Delete pending invitations
            $team->invitations()->delete();
            
            // Finally delete the team
            $team->delete();
        });

        return redirect()->route('teams.index')
                        ->with('success', 'Team deleted successfully.');
    }
}
