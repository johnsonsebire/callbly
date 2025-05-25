<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(Request $request, $id)
    {
        // Make sure only super-admin can impersonate
        if (!$request->user()->hasRole('super-admin|super admin')) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You do not have permission to impersonate users.');
        }

        // Find the user to impersonate
        $user = User::findOrFail($id);

        // Don't allow impersonating another super-admin
        if ($user->hasRole('super-admin')) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot impersonate another super-admin.');
        }

        // Store the admin's id in the session
        Session::put('admin_user_id', $request->user()->id);

        // Login as the impersonated user
        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', "You are now impersonating {$user->name}. To return to your account, click the 'Stop Impersonating' button in the header.");
    }

    /**
     * Stop impersonating and return to admin account
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stopImpersonating(Request $request)
    {
        // Get the original admin user id
        $adminId = Session::get('admin_user_id');

        if (!$adminId) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'You are not currently impersonating anyone.');
        }

        // Get the admin user
        $admin = User::findOrFail($adminId);

        // Login as the admin
        Auth::login($admin);

        // Remove the impersonation session variable
        Session::forget('admin_user_id');

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'You have stopped impersonating.');
    }
}
