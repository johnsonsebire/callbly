<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\SenderName;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        return view('teams.index', compact('ownedTeams', 'teams', 'user'));
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
            // Get the current user with their existing roles
            $user = auth()->user();
            
            $team = Team::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'owner_id' => $user->id,
                'personal_team' => false
            ]);

            // Store existing roles before attaching to team
            $existingRoles = $user->roles()->pluck('name')->toArray();
            
            $team->users()->attach(
                $user->id,
                ['role' => 'owner']
            );

            // No need to explicitly maintain roles as they are stored in a separate table
            // and not affected by the team creation process
            
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
        // Access check using User model's belongsToTeam method
        if (!auth()->user()->belongsToTeam($team)) {
            abort(403, 'You do not have access to this team.');
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
        // For AJAX requests, only clear flash messages (not entire session)
        if ($request->ajax() || $request->wantsJson()) {
            // Clear only flash messages, preserve authentication and other session data
            session()->forget(['_flash']);
            $request->session()->flash('_flash.old', []);
            $request->session()->flash('_flash.new', []);
            
            Log::info('Cleared flash messages for AJAX team update', [
                'team_id' => $team->id,
                'user_id' => auth()->id()
            ]);
        }
        
        // Log the start of the request
        Log::info('Team update request started', [
            'team_id' => $team->id,
            'user_id' => auth()->id(),
            'request_method' => $request->method(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'content_type' => $request->header('Content-Type'),
            'accept_header' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With'),
            'all_headers' => $request->headers->all(),
            'raw_input' => $request->all()
        ]);

        // Check if user can update team using ownsTeam method
        if (!auth()->user()->ownsTeam($team)) {
            Log::warning('Unauthorized team update attempt', [
                'team_id' => $team->id,
                'user_id' => auth()->id(),
                'is_ajax' => $request->ajax()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only team owners can update team settings.'
                ], 403);
            }
            abort(403, 'Only team owners can update team settings.');
        }

        try {
            // Process form data with proper boolean casting
            $data = $request->only(['name', 'description', 'share_sms_credits', 'share_contacts', 'share_sender_names', 'share_contact_groups']);
            
            Log::info('Processing team update data', [
                'team_id' => $team->id,
                'raw_data' => $data,
                'data_types' => array_map('gettype', $data)
            ]);
            
            // Explicitly cast checkbox values to boolean
            if (isset($data['share_sms_credits'])) {
                $data['share_sms_credits'] = filter_var($data['share_sms_credits'], FILTER_VALIDATE_BOOLEAN);
            }
            
            if (isset($data['share_contacts'])) {
                $data['share_contacts'] = filter_var($data['share_contacts'], FILTER_VALIDATE_BOOLEAN);
            }
            
            if (isset($data['share_sender_names'])) {
                $data['share_sender_names'] = filter_var($data['share_sender_names'], FILTER_VALIDATE_BOOLEAN);
            }
            
            if (isset($data['share_contact_groups'])) {
                $data['share_contact_groups'] = filter_var($data['share_contact_groups'], FILTER_VALIDATE_BOOLEAN);
            }
            
            Log::info('After boolean casting', [
                'team_id' => $team->id,
                'processed_data' => $data,
                'data_types' => array_map('gettype', $data)
            ]);
            
            // Validate after casting
            $validated = validator($data, [
                'name' => ['sometimes', 'string', 'max:255'],
                'description' => ['sometimes', 'nullable', 'string'],
                'share_sms_credits' => ['sometimes', 'boolean'],
                'share_contacts' => ['sometimes', 'boolean'],
                'share_sender_names' => ['sometimes', 'boolean'],
                'share_contact_groups' => ['sometimes', 'boolean']
            ])->validate();

            Log::info('Validation successful', [
                'team_id' => $team->id,
                'validated_data' => $validated
            ]);

            DB::transaction(function () use ($team, $validated) {
                // Update the team settings
                $team->update($validated);

                // Handle sender names sharing
                if (isset($validated['share_sender_names'])) {
                    if ($validated['share_sender_names']) {
                        $senderNames = SenderName::where('user_id', $team->owner_id)
                                            ->approved()
                                            ->get();
                        $team->syncResources($senderNames, 'sender_name');
                    } else {
                        $team->teamResources()
                            ->where('resource_type', 'sender_name')
                            ->delete();
                    }
                }

                // Handle contacts sharing
                if (isset($validated['share_contacts'])) {
                    if ($validated['share_contacts']) {
                        $contacts = Contact::where('user_id', $team->owner_id)->get();
                        $team->syncResources($contacts, 'contact');
                    } else {
                        $team->teamResources()
                            ->where('resource_type', 'contact')
                            ->delete();
                    }
                }

                // Handle contact groups sharing
                if (isset($validated['share_contact_groups'])) {
                    if ($validated['share_contact_groups']) {
                        $contactGroups = ContactGroup::where('user_id', $team->owner_id)->get();
                        $team->syncResources($contactGroups, 'contact_group');
                    } else {
                        $team->teamResources()
                            ->where('resource_type', 'contact_group')
                            ->delete();
                    }
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                // For AJAX: Return clean JSON response with no session flash messages
                Log::info('AJAX response sent successfully', [
                    'team_id' => $team->id,
                    'response' => ['success' => true, 'message' => 'Team settings updated successfully.']
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Team settings updated successfully.'
                ]);
            }

            return back()->with('success', 'Team settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Team update error: ' . $e->getMessage(), [
                'team_id' => $team->id,
                'user_id' => auth()->id(),
                'data' => $request->all(),
                'validated_data' => $validated ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                Log::info('AJAX error response sent', [
                    'team_id' => $team->id,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update team settings.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to update team settings.');
        }
    }

    /**
     * Remove the specified team from storage.
     */
    public function destroy(Team $team)
    {
        // Check if user can delete team using ownsTeam method
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can delete teams.');
        }

        if ($team->personal_team) {
            return back()->with('error', 'Cannot delete personal team.');
        }

        DB::transaction(function () use ($team) {
            $team->teamResources()->delete();
            $team->users()->detach();
            $team->invitations()->delete();
            $team->delete();
        });

        return redirect()->route('teams.index')
                        ->with('success', 'Team deleted successfully.');
    }

    /**
     * Update team member role.
     */
    public function updateMember(Request $request, Team $team, User $user)
    {
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can update member roles.');
        }

        $validated = $request->validate([
            'role' => ['required', 'string', 'in:' . implode(',', TeamUser::roles())]
        ]);

        if ($user->id === $team->owner_id) {
            return back()->with('error', 'Cannot change the role of the team owner.');
        }

        $team->users()->updateExistingPivot($user->id, [
            'role' => $validated['role']
        ]);

        return back()->with('success', 'Team member role updated successfully.');
    }

    /**
     * Remove team member.
     */
    public function removeMember(Team $team, User $user)
    {
        if (!auth()->user()->ownsTeam($team)) {
            abort(403, 'Only team owners can remove team members.');
        }

        if ($user->id === $team->owner_id) {
            return back()->with('error', 'Cannot remove the team owner.');
        }

        $team->users()->detach($user->id);

        return back()->with('success', 'Team member removed successfully.');
    }

    /**
     * Leave team.
     */
    public function leave(Team $team)
    {
        $user = auth()->user();

        if ($user->id === $team->owner_id) {
            return back()->with('error', 'Team owners cannot leave their own team.');
        }

        $team->users()->detach($user->id);

        return redirect()->route('teams.index')
                        ->with('success', 'You have left the team successfully.');
    }

    /**
     * Switch to team.
     */
    public function switchTeam(Team $team)
    {
        $user = auth()->user();

        if (!$user->belongsToTeam($team)) {
            abort(403, 'You do not have access to this team.');
        }

        $user->current_team_id = $team->id;
        $user->save();

        return back()->with('success', 'Current team switched successfully.');
    }
}
