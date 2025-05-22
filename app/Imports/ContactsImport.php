<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class ContactsImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @var int|null
     */
    protected $groupId;
    
    /**
     * @var array
     */
    protected $columnMapping;
    
    /**
     * @var array
     */
    protected $results = [
        'imported' => 0,
        'duplicates' => 0,
        'errors' => 0
    ];
    
    /**
     * ContactsImport constructor.
     * 
     * @param array $columnMapping
     * @param int|null $groupId
     */
    public function __construct(array $columnMapping, ?int $groupId = null)
    {
        $this->columnMapping = $columnMapping;
        $this->groupId = $groupId;
        
        Log::info('ContactsImport initialized with:', [
            'columnMapping' => $columnMapping,
            'groupId' => $groupId
        ]);
    }
    
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        Log::info('Processing import collection with ' . count($rows) . ' rows');
        
        if (count($rows) == 0) {
            Log::warning('No rows found in import file');
            return;
        }
        
        // Debug first row to verify column mapping
        if (isset($rows[0])) {
            Log::info('First row data for mapping:', $rows[0]->toArray());
        }
        
        foreach ($rows as $index => $row) {
            try {
                // Make sure we're not trying to process an empty row
                if ($row->isEmpty()) {
                    Log::info('Skipping empty row at index ' . $index);
                    continue;
                }
                
                // Get the numeric index or column name from the mapping
                $firstNameKey = $this->columnMapping['first_name'];
                $lastNameKey = $this->columnMapping['last_name'];
                $phoneKey = $this->columnMapping['phone'];
                $emailKey = $this->columnMapping['email'] ?? null;
                $companyKey = $this->columnMapping['company'] ?? null;
                $dateOfBirthKey = $this->columnMapping['date_of_birth'] ?? null;
                
                Log::debug("Row keys: " . json_encode($row->keys()->toArray()));
                Log::debug("Mapping keys: first_name={$firstNameKey}, last_name={$lastNameKey}, phone={$phoneKey}");
                
                // Access data directly from the row with the proper key (numeric or string)
                $first_name = null;
                $last_name = null;
                $phone_number = null;
                $email = null;
                $company = null;
                $date_of_birth = null;
                
                // Direct access by numeric index if that's what we have
                if (is_numeric($firstNameKey) && isset($row->values()[$firstNameKey])) {
                    $first_name = $row->values()[$firstNameKey];
                } elseif ($row->has($firstNameKey)) {
                    $first_name = $row[$firstNameKey];
                }
                
                if (is_numeric($lastNameKey) && isset($row->values()[$lastNameKey])) {
                    $last_name = $row->values()[$lastNameKey];
                } elseif ($row->has($lastNameKey)) {
                    $last_name = $row[$lastNameKey];
                }
                
                if (is_numeric($phoneKey) && isset($row->values()[$phoneKey])) {
                    $phone_number = $row->values()[$phoneKey];
                } elseif ($row->has($phoneKey)) {
                    $phone_number = $row[$phoneKey];
                }
                
                if ($emailKey) {
                    if (is_numeric($emailKey) && isset($row->values()[$emailKey])) {
                        $email = $row->values()[$emailKey];
                    } elseif ($row->has($emailKey)) {
                        $email = $row[$emailKey];
                    }
                }
                
                if ($companyKey) {
                    if (is_numeric($companyKey) && isset($row->values()[$companyKey])) {
                        $company = $row->values()[$companyKey];
                    } elseif ($row->has($companyKey)) {
                        $company = $row[$companyKey];
                    }
                }
                
                if ($dateOfBirthKey) {
                    if (is_numeric($dateOfBirthKey) && isset($row->values()[$dateOfBirthKey])) {
                        $date_of_birth = $row->values()[$dateOfBirthKey];
                    } elseif ($row->has($dateOfBirthKey)) {
                        $date_of_birth = $row[$dateOfBirthKey];
                    }
                }
                
                Log::debug("Row $index mapped data", [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_number' => $phone_number,
                    'email' => $email,
                    'company' => $company,
                    'date_of_birth' => $date_of_birth,
                ]);
                
                // Skip if no phone number
                if (empty($phone_number)) {
                    $this->results['errors']++;
                    Log::warning("Row $index skipped: No phone number");
                    continue;
                }
                
                // Check for duplicate
                $existingContact = Contact::where('user_id', Auth::id())
                    ->where('phone_number', $phone_number)
                    ->first();
                    
                if ($existingContact) {
                    $this->results['duplicates']++;
                    Log::info("Row $index skipped: Duplicate phone number $phone_number");
                    continue;
                }
                
                // Create new contact
                $contact = new Contact([
                    'user_id' => Auth::id(),
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_number' => $phone_number,
                    'email' => $email,
                    'company' => $company,
                    'date_of_birth' => $date_of_birth
                ]);
                
                $contact->save();
                $this->results['imported']++;
                
                // Add to group if specified
                if ($this->groupId) {
                    $contact->groups()->attach($this->groupId);
                    Log::info("Contact with phone $phone_number added to group $this->groupId");
                }
                
            } catch (\Exception $e) {
                $this->results['errors']++;
                Log::error("Error importing row $index: " . $e->getMessage());
                Log::error($e->getTraceAsString());
            }
        }
        
        Log::info('Import completed with results:', $this->results);
    }
    
    /**
     * Get import results
     * 
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
    
    /**
     * Validation rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}