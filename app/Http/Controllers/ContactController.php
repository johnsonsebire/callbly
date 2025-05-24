<?php

namespace App\Http\Controllers;

use App\Imports\ContactsImport;
use App\Exports\ContactsExport;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use League\Csv\Writer;
use Maatwebsite\Excel\Facades\Excel;
use SplTempFileObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all available contacts (personal + shared from teams)
        $allContacts = $user->getAvailableContacts();
        
        // Paginate the collection manually
        $perPage = 15;
        $currentPage = request()->input('page', 1);
        
        $contacts = new \Illuminate\Pagination\LengthAwarePaginator(
            $allContacts->forPage($currentPage, $perPage),
            $allContacts->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
        
        // Check if the user has shared contacts
        $hasSharedContacts = $user->canUseTeamResource('contacts');
        
        return view('contacts.index', compact('contacts', 'hasSharedContacts'));
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
            'date_of_birth' => 'nullable|date',
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
            'date_of_birth' => $validated['date_of_birth'] ?? null,
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
            'date_of_birth' => 'nullable|date',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'groups' => 'nullable|array',
        ]);

        $contact->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
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
        // Add direct debug logging at the start of the method
        Log::info('uploadImport method called', ['has_file' => $request->hasFile('excel_file')]);
        
        try {
            $request->validate([
                'excel_file' => 'required|file|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,text/plain|max:10240',
                'group_id' => 'nullable|exists:contact_groups,id'
            ]);
            
            Log::info('File upload validation passed');
            
            if ($request->hasFile('excel_file')) {
                try {
                    // Create the temp directory if it doesn't exist
                    $tempPath = storage_path('app/temp');
                    if (!file_exists($tempPath)) {
                        mkdir($tempPath, 0755, true);
                        Log::info('Created temp directory: ' . $tempPath);
                    }
                    
                    // Store the file using Laravel Storage facade
                    $file = $request->file('excel_file');
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time() . '.' . $extension;
                    
                    // Store the file using Laravel's Storage facade
                    $path = Storage::putFileAs('temp', $file, $fileName);
                    Log::info('File stored at path: ' . $path);
                    
                    // Check if file was stored properly
                    if (!Storage::exists($path)) {
                        Log::error('File not found after storage with Storage facade: ' . $path);
                        return redirect()->back()->with('error', 'Failed to upload file: File not found after storage.');
                    }
                    
                    // Get the absolute file path for debugging
                    $filePath = Storage::path($path);
                    Log::info('Absolute file path: ' . $filePath);
                    
                    // Double check file exists using PHP's file_exists
                    if (!file_exists($filePath)) {
                        Log::error('File not found at absolute path: ' . $filePath);
                        return redirect()->back()->with('error', 'Failed to upload file: File not found at absolute path.');
                    }
                    
                    Log::info('About to process Excel file: ' . $filePath);
                    
                    // Use Laravel Excel to load the file by using Storage disk path
                    $collection = Excel::toCollection(null, $path, 'local');
                    Log::info('Excel collection created', ['collection_empty' => $collection->isEmpty()]);
                    
                    if ($collection->isEmpty() || $collection[0]->isEmpty()) {
                        Log::warning('The uploaded file does not contain any data');
                        return redirect()->back()->with('error', 'The uploaded file does not contain any data.');
                    }
                    
                    // Get the headers (first row)
                    $headers = $collection[0][0]->keys()->toArray();
                    Log::info('Headers found', ['headers' => $headers]);
                    
                    if (empty($headers)) {
                        Log::warning('No headers found in the file');
                        return redirect()->back()->with('error', 'The file does not contain any headers. Please ensure the first row contains column names.');
                    }
                    
                    // Preview the first 5 rows
                    $records = $collection[0]->take(5);
                    $totalRecords = count($collection[0]) - 1; // Excluding header row
                    Log::info('Preview data prepared', [
                        'preview_records' => count($records),
                        'total_records' => $totalRecords
                    ]);
                    
                    return view('contacts.import_preview', [
                        'records' => $records,
                        'headers' => $headers,
                        'path' => $path,
                        'group_id' => $request->group_id,
                        'total_records' => $totalRecords,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Exception in file processing: ' . $e->getMessage());
                    Log::error($e->getTraceAsString());
                    return redirect()->back()->with('error', 'Failed to process file: ' . $e->getMessage());
                }
            } else {
                Log::warning('No file was found in the request');
                return redirect()->back()->with('error', 'No file was selected or the upload failed.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation exception: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected exception: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Process the imported Excel/CSV file
     */
    public function processImport(Request $request)
    {
        try {
            // Log the incoming request data for debugging
            Log::info('Import process started with data:', $request->all());
            
            $validated = $request->validate([
                'path' => 'required|string',
                'first_name_column' => 'required|string',
                'last_name_column' => 'required|string',
                'phone_column' => 'required|string',
                'email_column' => 'nullable|string',
                'company_column' => 'nullable|string',
                'date_of_birth_column' => 'nullable|string',
                'group_id' => 'nullable|exists:contact_groups,id'
            ]);

            Log::info('Validation passed with data:', $validated);

            // Check if file exists using Storage facade
            if (!Storage::exists($validated['path'])) {
                Log::error('Import file not found using Storage: ' . $validated['path']);
                return redirect()->route('contacts.import')->with('error', 'Import file not found. Please upload again.');
            }
            
            // Get the absolute file path for additional verification
            $filePath = Storage::path($validated['path']);
            Log::info('Absolute file path for import: ' . $filePath);
            
            try {
                // Create column mapping
                $columnMapping = [
                    'first_name' => $validated['first_name_column'],
                    'last_name' => $validated['last_name_column'],
                    'phone' => $validated['phone_column'],
                ];
                
                // Log the column mapping for debugging
                Log::info('Column mapping:', $columnMapping);
                
                if (!empty($validated['email_column'])) {
                    $columnMapping['email'] = $validated['email_column'];
                }
                
                if (!empty($validated['company_column'])) {
                    $columnMapping['company'] = $validated['company_column'];
                }
                
                if (!empty($validated['date_of_birth_column'])) {
                    $columnMapping['date_of_birth'] = $validated['date_of_birth_column'];
                }
                
                // Start import transaction
                DB::beginTransaction();
                
                // Create new import instance
                $groupId = !empty($validated['group_id']) ? (int)$validated['group_id'] : null;
                $import = new ContactsImport($columnMapping, $groupId);
                
                // Import the file using Storage facade path
                Log::info('Starting Excel import for file: ' . $validated['path']);
                try {
                    Excel::import($import, $validated['path'], 'local');
                    Log::info('Excel import completed successfully');
                } catch (\Exception $e) {
                    Log::error('Excel import threw exception: ' . $e->getMessage());
                    Log::error($e->getTraceAsString());
                    throw $e; // Rethrow to be caught by the outer catch
                }
                
                // Get results
                $results = $import->getResults();
                Log::info('Import results:', $results);
                
                DB::commit();
                
                // Cleanup temporary file
                Storage::delete($validated['path']);
                Log::info('Temporary file deleted: ' . $validated['path']);
                
                return redirect()->route('contacts.index')->with('success', 
                    "Import complete: {$results['imported']} contacts imported, {$results['duplicates']} duplicates skipped, {$results['errors']} errors encountered.");
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Exception during import process: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
                return redirect()->route('contacts.import')->with('error', 'Import failed: ' . $e->getMessage());
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Exception during request processing: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->route('contacts.import')->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the export form
     */
    public function export()
    {
        $user = Auth::user();
        $groups = ContactGroup::where('user_id', $user->id)->pluck('name', 'id');
        $allContacts = Contact::where('user_id', $user->id)
            ->select('id', 'first_name', 'last_name', 'phone_number', 'email')
            ->orderBy('first_name')
            ->get();
            
        return view('contacts.export', compact('groups', 'allContacts'));
    }

    /**
     * Process the contact export
     */
    public function processExport(Request $request)
    {
        $user = Auth::user();
        $query = Contact::where('user_id', $user->id);
        
        // Filter based on export type
        if ($request->export_type === 'group' && $request->filled('group_id')) {
            $query->whereHas('groups', function($q) use ($request) {
                $q->where('contact_groups.id', $request->group_id);
            });
        } elseif ($request->export_type === 'selected' && $request->filled('selected_contacts')) {
            $selectedIds = explode(',', $request->selected_contacts);
            $query->whereIn('id', $selectedIds);
        }
        
        // Filter columns to include
        $columns = $request->columns ?? ['first_name', 'last_name', 'phone_number', 'email', 'company', 'notes'];
        
        $contacts = $query->get($columns);
        
        // Define column headers for the export
        $headers = [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'date_of_birth' => 'Date of Birth',
            'company' => 'Company',
            'notes' => 'Notes'
        ];
        
        // Filter headers to only include requested columns
        $exportHeaders = array_intersect_key($headers, array_flip($columns));
        
        // Generate filename
        $timestamp = date('Y-m-d_H-i-s');
        $filename = 'contacts_export_' . $timestamp;
        
        // Export based on requested format
        $format = $request->format ?? 'csv';
        
        if ($format === 'pdf') {
            // PDF Export
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contacts.export_pdf', [
                'contacts' => $contacts,
                'headers' => $exportHeaders
            ]);
            
            return $pdf->download($filename . '.pdf');
            
        } elseif ($format === 'excel') {
            // Excel Export using Laravel Excel
            return Excel::download(new ContactsExport($contacts, $exportHeaders), $filename . '.xlsx');
            
        } else {
            // CSV Export (default)
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            
            // Add headers
            $csv->insertOne(array_values($exportHeaders));
            
            // Add data rows
            foreach ($contacts as $contact) {
                $row = [];
                foreach ($columns as $column) {
                    $row[] = $contact->{$column} ?? '';
                }
                $csv->insertOne($row);
            }
            
            // Return the CSV as a download
            return response((string) $csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
        }
    }
}
