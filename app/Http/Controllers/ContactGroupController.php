<?php

namespace App\Http\Controllers;

use App\Models\ContactGroup;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactGroupController extends Controller
{
    /**
     * Display a listing of the contact groups.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get all available contact groups (personal + shared from teams)
        $allGroups = $user->getAvailableContactGroups();
        
        // Filter by search term if provided
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $allGroups = $allGroups->filter(function($group) use ($searchTerm) {
                return Str::contains(strtolower($group->name), strtolower($searchTerm)) || 
                       Str::contains(strtolower($group->description ?? ''), strtolower($searchTerm));
            });
        }
        
        // Check if user has access to shared contact groups
        $hasSharedGroups = $user->canUseTeamResource('contact_groups');
        
        // Paginate the collection manually
        $perPage = 20;
        $currentPage = $request->input('page', 1);
        
        $groups = new \Illuminate\Pagination\LengthAwarePaginator(
            $allGroups->forPage($currentPage, $perPage),
            $allGroups->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('contact-groups.index', compact('groups', 'hasSharedGroups'));
    }
    
    /**
     * Show the form for creating a new contact group.
     */
    public function create()
    {
        return view('contact-groups.create');
    }
    
    /**
     * Store a newly created contact group in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $group = new ContactGroup();
        $group->user_id = Auth::id();
        $group->name = $validatedData['name'];
        $group->description = $validatedData['description'] ?? null;
        $group->save();
        
        return redirect()->route('contact-groups.index')
            ->with('success', 'Contact group created successfully.');
    }
    
    /**
     * Display the specified contact group.
     */
    public function show(ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load contacts with pagination
        $contacts = $contactGroup->contacts()->paginate(20);
        
        return view('contact-groups.show', [
            'group' => $contactGroup,
            'contacts' => $contacts
        ]);
    }

    /**
     * Search contacts within a group for AJAX requests.
     */
    public function searchContacts(Request $request, ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $query = $contactGroup->contacts();

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('phone_number', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('company', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'first_name');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        $allowedSortFields = ['first_name', 'last_name', 'phone_number', 'email', 'company', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        $perPage = $request->get('per_page', 20);
        $contacts = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'contacts' => $contacts->items(),
                    'total' => $contacts->total(),
                    'current_page' => $contacts->currentPage(),
                    'last_page' => $contacts->lastPage(),
                    'per_page' => $contacts->perPage(),
                    'pagination_links' => $contacts->appends($request->query())->links()->render()
                ]
            ]);
        }

        return redirect()->route('contact-groups.show', $contactGroup);
    }
    
    /**
     * Show the form for editing the specified contact group.
     */
    public function edit(ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('contact-groups.edit', [
            'group' => $contactGroup
        ]);
    }
    
    /**
     * Update the specified contact group in storage.
     */
    public function update(Request $request, ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $contactGroup->name = $validatedData['name'];
        $contactGroup->description = $validatedData['description'] ?? null;
        $contactGroup->save();
        
        return redirect()->route('contact-groups.show', $contactGroup->id)
            ->with('success', 'Contact group updated successfully.');
    }
    
    /**
     * Remove the specified contact group from storage.
     */
    public function destroy(ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Detach all contacts first (the pivot relationships)
        $contactGroup->contacts()->detach();
        
        // Delete the group
        $contactGroup->delete();
        
        return redirect()->route('contact-groups.index')
            ->with('success', 'Contact group deleted successfully.');
    }
    
    /**
     * Add contacts to a group.
     */
    public function addContacts(Request $request, ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validatedData = $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id',
        ]);
        
        // Check that all contacts belong to the user
        $userContactIds = Auth::user()->contacts()
            ->whereIn('id', $validatedData['contact_ids'])
            ->pluck('id')
            ->toArray();
        
        // Add contacts to the group
        $contactGroup->contacts()->syncWithoutDetaching($userContactIds);
        
        return redirect()->route('contact-groups.show', $contactGroup->id)
            ->with('success', count($userContactIds) . ' contacts added to group.');
    }
    
    /**
     * Store contacts to a group.
     */
    public function storeContacts(Request $request, ContactGroup $contactGroup)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validatedData = $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id',
        ]);
        
        // Check that all contacts belong to the user
        $userContactIds = Auth::user()->contacts()
            ->whereIn('id', $validatedData['contact_ids'])
            ->pluck('id')
            ->toArray();
        
        // Add contacts to the group
        $contactGroup->contacts()->syncWithoutDetaching($userContactIds);
        
        return redirect()->route('contact-groups.show', $contactGroup->id)
            ->with('success', count($userContactIds) . ' contacts added to group.');
    }
    
    /**
     * Remove a contact from a group.
     */
    public function removeContact(ContactGroup $contactGroup, $contactId)
    {
        // Verify ownership
        if ($contactGroup->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Verify the contact belongs to the user
        $userContact = Auth::user()->contacts()->find($contactId);
        
        if (!$userContact) {
            abort(404, 'Contact not found.');
        }
        
        // Remove the contact from the group
        $contactGroup->contacts()->detach($contactId);
        
        return redirect()->route('contact-groups.show', $contactGroup->id)
            ->with('success', 'Contact removed from group.');
    }
}