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
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
                $existingUser->notify(new TeamInvitationNotification($invitation));
            } else {
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
        $invitation = TeamInvitation::where('token', $token)
            ->with('team')
            ->first();
        
        if (!$invitation || $invitation->hasExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired or is invalid.');
        }

        // For guests, show login page with the invitation token stored in session
        if (!auth()->check()) {
            session(['team_invitation_token' => $token]);
            return redirect()->route('login', ['email' => $invitation->email])
                ->with('message', 'Please log in or create an account to join the team.');
        }

        // If user is logged in and invitation matches their email
        if (auth()->user()->email === $invitation->email) {
            return view('teams.invitations.accept', [
                'invitation' => $invitation,
            ]);
        }

        // If user is logged in but with wrong email
        return view('teams.invitations.wrong-account', [
            'invitation' => $invitation,
            'invitedEmail' => $invitation->email,
        ]);
    }
    
    /**
     * Accept an invitation.
     */
    public function accept(Request $request, string $token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->with('team.owner')
            ->first();
        
        if (!$invitation || $invitation->hasExpired()) {
            return redirect()->route('login')
                ->with('error', 'This invitation has expired or is invalid.');
        }
        
        $user = Auth::user();
        
        // If user is not logged in, store invitation token in session and redirect to register
        if (!$user) {
            session(['team_invitation_token' => $token]);
            return redirect()->route('register', ['email' => $invitation->email])
                ->with('message', 'Please create an account to join the team.');
        }
        
        // Check if the invitation was meant for this user
        if ($invitation->email !== $user->email) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Please log in with the email address that received the invitation (' . $invitation->email . ')');
        }

        // Check if user is already on the team
        if ($invitation->team->hasUser($user)) {
            return back()->with('error', 'You are already a member of this team.');
        }
        
        // Add user to team with the specified role
        $invitation->team->users()->attach($user->id, ['role' => $invitation->role]);

        // Make sure roles exist
        if (!Role::where('name', 'team-admin')->exists()) {
            Role::create(['name' => 'team-admin']);
        }
        if (!Role::where('name', 'team-member')->exists()) {
            Role::create(['name' => 'team-member']);
        }

        // Create necessary permissions if they don't exist
        $permissions = [
            'manage-team-settings',
            'invite-team-members',
            'remove-team-members',
            'view-team-billing',
            'access-team-resources',
            'view-team-dashboard'
        ];

        foreach ($permissions as $permissionName) {
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create(['name' => $permissionName]);
            }
        }
        
        // Assign team role and permissions
        if ($invitation->role === 'admin') {
            $user->assignRole('team-admin');
            $user->givePermissionTo([
                'manage-team-settings',
                'invite-team-members',
                'remove-team-members',
                'view-team-billing'
            ]);
        } else {
            $user->assignRole('team-member');
            $user->givePermissionTo([
                'access-team-resources',
                'view-team-dashboard'
            ]);
        }
        
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
        
        if ($invitation->team_id !== $team->id) {
            abort(404);
        }
        
        $invitation->delete();
        
        return back()->with('success', 'Invitation canceled successfully.');
    }
}
