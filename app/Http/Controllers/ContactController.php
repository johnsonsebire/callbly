<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index()
    {
        $user = Auth::user();
        $contacts = Contact::where('user_id', $user->id)->paginate(10);
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        $user = Auth::user();
        $groups = ContactGroup::where('user_id', $user->id)->pluck('name', 'id');
        return view('contacts.create', compact('groups'));
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'groups' => 'nullable|array',
        ]);

        $contact = Contact::create([
            'user_id' => Auth::id(),
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'] ?? null,
            'company' => $validated['company'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        if (!empty($validated['groups'])) {
            $contact->groups()->attach($validated['groups']);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact)
    {
        // Authorization check
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact)
    {
        // Authorization check
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }
        
        $user = Auth::user();
        $groups = ContactGroup::where('user_id', $user->id)->pluck('name', 'id');
        $selectedGroups = $contact->groups->pluck('id')->toArray();
        
        return view('contacts.edit', compact('contact', 'groups', 'selectedGroups'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        // Authorization check
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'groups' => 'nullable|array',
        ]);

        $contact->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'] ?? null,
            'company' => $validated['company'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Sync groups
        $contact->groups()->sync($validated['groups'] ?? []);

        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Contact $contact)
    {
        // Authorization check
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }
        
        $contact->groups()->detach(); // Remove from all groups first
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Show the import form
     */
    public function import()
    {
        $user = Auth::user();
        $groups = ContactGroup::where('user_id', $user->id)->pluck('name', 'id');
        return view('contacts.import', compact('groups'));
    }

    /**
     * Handle file upload for import
     */
    public function uploadImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
            'group_id' => 'nullable|exists:contact_groups,id'
        ]);
        
        if ($request->hasFile('csv_file')) {
            $path = $request->file('csv_file')->store('temp');
            
            // Parse the CSV file to preview the data
            $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
            $csv->setHeaderOffset(0);
            
            $records = collect($csv->getRecords())->take(5);
            $headers = $csv->getHeader();
            
            return view('contacts.import_preview', [
                'records' => $records,
                'headers' => $headers,
                'path' => $path,
                'group_id' => $request->group_id,
                'total_records' => iterator_count($csv->getRecords()),
            ]);
        }
        
        return redirect()->back()->with('error', 'Failed to upload file.');
    }

    /**
     * Process the imported CSV
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'path' => 'required',
            'name_column' => 'required',
            'phone_column' => 'required',
            'email_column' => 'nullable',
            'company_column' => 'nullable',
            'group_id' => 'nullable|exists:contact_groups,id'
        ]);
        
        try {
            $path = storage_path('app/' . $request->path);
            
            // Check if file exists
            if (!file_exists($path)) {
                return redirect()->route('contacts.import')
                    ->with('error', 'The uploaded file could not be found. Please try uploading again.');
            }
            
            $csv = Reader::createFromPath($path, 'r');
            $csv->setHeaderOffset(0);
            
            $user = Auth::user();
            $counter = 0;
            $errors = [];
            
            foreach ($csv as $index => $record) {
                if (!isset($record[$request->phone_column]) || empty($record[$request->phone_column])) {
                    $errors[] = "Row #" . ($index + 2) . ": Missing phone number";
                    continue;
                }
                
                try {
                    // Split the name into first and last name
                    $fullName = $record[$request->name_column] ?? 'Unknown';
                    $nameParts = explode(' ', $fullName, 2);
                    $firstName = $nameParts[0];
                    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                    
                    $contact = Contact::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'phone_number' => $record[$request->phone_column]
                        ],
                        [
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => isset($request->email_column) && isset($record[$request->email_column]) 
                                ? $record[$request->email_column] : null,
                            'company' => isset($request->company_column) && isset($record[$request->company_column])
                                ? $record[$request->company_column] : null,
                        ]
                    );
                    
                    if ($request->group_id) {
                        $contact->groups()->syncWithoutDetaching([$request->group_id]);
                    }
                    
                    $counter++;
                } catch (\Exception $e) {
                    $errors[] = "Row #" . ($index + 2) . ": " . $e->getMessage();
                }
            }
            
            // Clean up the temporary file
            @unlink($path);
            
            return redirect()->route('contacts.index')->with([
                'success' => "{$counter} contacts imported successfully",
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            return redirect()->route('contacts.import')
                ->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    /**
     * Export contacts to CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = Contact::where('user_id', $user->id);
        
        // Filter by group if specified
        if ($request->has('group_id') && $request->group_id) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('contact_groups.id', $request->group_id);
            });
        }
        
        $contacts = $query->get(['first_name', 'last_name', 'phone_number', 'email', 'company', 'notes']);
        
        // Create CSV writer
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        
        // Add CSV headers
        $csv->insertOne(['First Name', 'Last Name', 'Phone Number', 'Email', 'Company', 'Notes']);
        
        // Add data rows
        foreach ($contacts as $contact) {
            $csv->insertOne([
                $contact->first_name,
                $contact->last_name,
                $contact->phone_number,
                $contact->email ?? '',
                $contact->company ?? '',
                $contact->notes ?? ''
            ]);
        }
        
        $filename = 'contacts_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        // Return the CSV as a download
        return response((string) $csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
