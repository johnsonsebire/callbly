<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeamInvitation;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required', 
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'g-recaptcha-response' => ['required_if:recaptcha.enable,true', 'recaptcha']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'call_credits' => 0,
            'sms_credits' => 0,
            'ussd_credits' => 0,
        ]);
        
        // Send welcome email notification
        $user->notify(new WelcomeEmailNotification($user));

        auth()->login($user);

        // Fire the Registered event to trigger free SMS credits and other welcome processes
        event(new \Illuminate\Auth\Events\Registered($user));

        // Check if there was a pending team invitation
        if ($invitationToken = session('team_invitation_token')) {
            $invitation = TeamInvitation::where('token', $invitationToken)
                ->where('email', $user->email)
                ->first();

            if ($invitation && !$invitation->hasExpired()) {
                // Add user to team with the specified role
                $invitation->team->users()->attach($user->id, ['role' => $invitation->role]);
                
                // Set this as the user's current team
                $user->current_team_id = $invitation->team_id;
                $user->save();

                // Assign team role and permissions
                if ($invitation->role === 'admin') {
                    $user->assignRole('team-admin');
                    $user->givePermissionTo([
                        'manage-team-settings',
                        'invite-team-members',
                        'remove-team-members',
                        'view-team-billing',
                    ]);
                } else {
                    $user->assignRole('team-member');
                    $user->givePermissionTo([
                        'access-team-resources',
                        'view-team-dashboard',
                    ]);
                }

                // Delete the invitation
                $invitation->delete();
                session()->forget('team_invitation_token');

                return redirect()->route('teams.show', $invitation->team)
                    ->with('success', 'You have joined the team successfully.');
            }
        }

        // If no team invitation, assign default customer role
        // Get or create system team for role assignment
        $systemTeam = \App\Models\Team::firstOrCreate([
            'name' => 'System',
            'slug' => 'system'
        ], [
            'owner_id' => 1, // Default to first user
            'description' => 'System-wide roles and permissions'
        ]);

        // Set team context for permission system
        app(\Spatie\Permission\PermissionRegistrar::class)->setPermissionsTeamId($systemTeam->id);
        
        // Ensure customer role exists and assign it
        $customerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'customer']);
        
        // Set user's current team if they don't have one
        if (!$user->current_team_id) {
            $user->current_team_id = $systemTeam->id;
            $user->save();
        }
        
        $user->assignRole('customer');

        return redirect()->route('dashboard');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}