<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users for Super Admin.
     */
    public function index(Request $request)
    {
        // Retrieve all users with pagination
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        
        // Get all roles for the role assignment functionality
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'email_verified_at' => now(), // Admin created users are verified by default
        ]);

        // Assign roles if any were selected
        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        // Update user information
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
        ];

        // Only update password if one was provided
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Update status if provided
        if (isset($validated['status'])) {
            $userData['email_verified_at'] = $validated['status'] ? now() : null;
        }

        $user->update($userData);

        // Update roles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deletion of own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Update user roles directly.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Get the role models based on the IDs to access their names
        $roleModels = Role::whereIn('id', $request->roles)->get();
        
        // Extract the role names from the models
        $roleNames = $roleModels->pluck('name')->toArray();
        
        // Sync the user's roles with the selected ones (using names, not IDs)
        $user->syncRoles($roleNames);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Roles for {$user->name} updated successfully.");
    }

    /**
     * Add SMS credits to a user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addCredits(Request $request, User $user)
    {
        $validated = $request->validate([
            'credits' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
        ]);

        // Begin transaction
        \DB::beginTransaction();

        try {
            // Get current credits or set to 0 if null
            $currentCredits = $user->sms_credits ?? 0;
            
            // Add the new credits to the user's account
            $user->sms_credits = $currentCredits + $validated['credits'];
            $user->save();

            // Log the credit addition for tracking
            \DB::table('sms_credit_logs')->insert([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'credits' => $validated['credits'],
                'note' => $validated['note'] ?? null,
                'type' => 'admin_topup',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', "{$validated['credits']} SMS credits have been added to {$user->name}'s account.");
        } catch (\Exception $e) {
            \DB::rollBack();
            
            return redirect()
                ->route('admin.users.index')
                ->with('error', "Failed to add credits: {$e->getMessage()}");
        }
    }
}