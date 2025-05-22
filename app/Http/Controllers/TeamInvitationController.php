<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TeamInvitationController extends Controller
{
    /**
     * Display the invite form.
     */
    public function create(Team $team)
    {
        if (! Gate::allows('invite-to-team', $team)) {
            abort(403);
        }
        
        return view('teams.invitations.create', [
            'team' => $team,
        ]);
    }
    
    /**
     * Store a newly created invitation in storage.
     */
    public function store(Request $request, Team $team)
    {
        if (! Gate::allows('invite-to-team', $team)) {
            abort(403);
        }
        
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:admin,member'],
        ]);
        
        // Check if the user is already on the team
        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser && $team->hasUser($existingUser)) {
            return back()->withErrors([
                'email' => 'This user is already a member of the team.',
            ])->withInput();
        }
        
        // Check if there's a pending invitation for this email
        $existingInvitation = $team->invitations()->where('email', $validated['email'])->first();
        if ($existingInvitation) {
            // Refresh the invitation instead of creating a new one
            $existingInvitation->update([
                'role' => $validated['role'],
                'expires_at' => now()->addDays(7),
            ]);
            
            // Send the invitation email
            try {
                $existingInvitation->sendInvitation();
                return back()->with('success', 'Invitation has been resent successfully.');
            } catch (\Exception $e) {
                return back()->with('error', 'We encountered an issue sending the invitation. Please try again.');
            }
        }
        
        // Create a new invitation
        $invitation = $team->invitations()->create([
            'email' => $validated['email'],
            'role' => $validated['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);
        
        try {
            // Send invitation email
            if ($existingUser) {
                // If user exists but is not on team, send notification to existing user
                $existingUser->notify(new TeamInvitationNotification($invitation));
            } else {
                // Send email invitation to new user
                Notification::route('mail', $validated['email'])
                    ->notify(new TeamInvitationNotification($invitation));
            }
            
            return back()->with('success', 'Invitation sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'We encountered an issue sending the invitation. Please try again.');
        }
    }
    
    /**
     * Display the specified invitation.
     */
    public function show(string $token)
    {
        $invitation = TeamInvitation::where('token', $token)->first();
        
        if (!$invitation || $invitation->hasExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired or is invalid.');
        }
        
        return view('teams.invitations.show', [
            'invitation' => $invitation,
        ]);
    }
    
    /**
     * Accept an invitation.
     */
    public function accept(Request $request, string $token)
    {
        $invitation = TeamInvitation::where('token', $token)->first();
        
        if (!$invitation || $invitation->hasExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired or is invalid.');
        }
        
        $user = Auth::user();
        
        // If user is not logged in, redirect to register with invitation info
        if (!$user) {
            return redirect()->route('register', ['invitation' => $token]);
        }
        
        // Check if the invitation was meant for this user
        if ($invitation->email !== $user->email) {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation was not meant for your account.');
        }
        
        // Add user to team with the specified role
        $invitation->team->users()->attach($user->id, [
            'role' => $invitation->role,
        ]);
        
        // Set this as the user's current team if they don't have one
        if (!$user->current_team_id) {
            $user->current_team_id = $invitation->team_id;
            $user->save();
        }
        
        // Delete the invitation
        $invitation->delete();
        
        return redirect()->route('teams.show', $invitation->team)
            ->with('success', 'You have joined the team successfully.');
    }
    
    /**
     * Decline an invitation.
     */
    public function decline(string $token)
    {
        $invitation = TeamInvitation::where('token', $token)->first();
        
        if (!$invitation || $invitation->hasExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired or is invalid.');
        }
        
        $invitation->delete();
        
        return redirect()->route('dashboard')
            ->with('success', 'Team invitation declined.');
    }
    
    /**
     * Cancel an invitation (by team owner/admin).
     */
    public function destroy(Team $team, TeamInvitation $invitation)
    {
        if (! Gate::allows('invite-to-team', $team)) {
            abort(403);
        }
        
        // Ensure the invitation belongs to this team
        if ($invitation->team_id !== $team->id) {
            abort(404);
        }
        
        $invitation->delete();
        
        return back()->with('success', 'Invitation canceled successfully.');
    }
}
