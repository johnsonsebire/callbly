<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required_if:recaptcha.enable,true', 'recaptcha']
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Check if the authenticated user has any roles, if not assign customer role
            $user = Auth::user();
            if ($user->roles()->count() === 0) {
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
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}