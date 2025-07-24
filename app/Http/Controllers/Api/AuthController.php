<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'call_credits' => 0,
            'sms_credits' => 5, // Give 5 free SMS credits on registration
            'ussd_credits' => 0,
        ]);

        // Check if user was invited (session contains invitation token)
        $invitationToken = session('team_invitation_token');
        $isInvitedUser = false;
        
        if ($invitationToken) {
            // Check for valid team invitation
            $invitation = \App\Models\TeamInvitation::where('token', $invitationToken)
                ->where('email', $user->email)
                ->first();
                
            if ($invitation && !$invitation->hasExpired()) {
                $isInvitedUser = true;
                // Handle team role assignment (done in RegisterController)
            }
        }
        
        // Assign default customer role only if not an invited user
        if (!$isInvitedUser) {
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
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * Login user and create token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        
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
        
        // Revoke all existing tokens
        $user->tokens()->delete();
        
        // Create new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * Get the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    /**
     * Logout user (revoke token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Verify device for biometric authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'device_name' => 'required|string',
            'biometric_enabled' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Find or create device record
        $device = UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_id' => $request->device_id,
            ],
            [
                'device_name' => $request->device_name,
                'biometric_enabled' => $request->biometric_enabled,
                'last_authenticated_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Device verified successfully',
            'data' => [
                'device' => $device,
                'biometric_enabled' => $device->biometric_enabled
            ]
        ]);
    }
}