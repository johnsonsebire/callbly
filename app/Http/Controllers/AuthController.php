<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'company_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_name' => $request->company_name,
        ]);

        // Assign default customer role for new registrations
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Login user and create token
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        
        // Check if user has any roles assigned, if not assign customer role
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
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Get the authenticated user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout user (revoke token)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Update user profile
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $request->user()->id,
            'company_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        $user->update($request->only([
            'name', 'email', 'company_name', 'phone', 'address'
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
}