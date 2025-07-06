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
            Log::info('Available row keys:', $rows[0]->keys()->toArray());
        }
        
        foreach ($rows as $index => $row) {
            try {
                // Make sure we're not trying to process an empty row
                if ($row->isEmpty()) {
                    Log::info('Skipping empty row at index ' . $index);
                    continue;
                }
                
                // Get the numeric index or column name from the mapping
                // Note: Laravel Excel with WithHeadingRow converts headers to snake_case and lowercase
                $firstNameKey = $this->convertHeaderToLaravelExcelFormat($this->columnMapping['first_name']);
                $lastNameKey = $this->convertHeaderToLaravelExcelFormat($this->columnMapping['last_name']);
                $phoneKey = $this->convertHeaderToLaravelExcelFormat($this->columnMapping['phone']);
                $emailKey = isset($this->columnMapping['email']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['email']) : null;
                $companyKey = isset($this->columnMapping['company']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['company']) : null;
                $dateOfBirthKey = isset($this->columnMapping['date_of_birth']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['date_of_birth']) : null;
                $genderKey = isset($this->columnMapping['gender']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['gender']) : null;
                $countryKey = isset($this->columnMapping['country']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['country']) : null;
                $regionKey = isset($this->columnMapping['region']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['region']) : null;
                $cityKey = isset($this->columnMapping['city']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['city']) : null;
                
                Log::debug("Row keys: " . json_encode($row->keys()->toArray()));
                Log::debug("Original mapping keys: first_name={$this->columnMapping['first_name']}, last_name={$this->columnMapping['last_name']}, phone={$this->columnMapping['phone']}");
                Log::debug("Converted mapping keys: first_name={$firstNameKey}, last_name={$lastNameKey}, phone={$phoneKey}");
                
                // Access data directly from the row using Laravel Excel converted keys
                $first_name = $row[$firstNameKey] ?? null;
                $last_name = $row[$lastNameKey] ?? null;
                $phone_number = $row[$phoneKey] ?? null;
                $email = $emailKey ? ($row[$emailKey] ?? null) : null;
                $company = $companyKey ? ($row[$companyKey] ?? null) : null;
                $date_of_birth = $dateOfBirthKey ? ($row[$dateOfBirthKey] ?? null) : null;
                $gender = $genderKey ? ($row[$genderKey] ?? null) : null;
                $country = $countryKey ? ($row[$countryKey] ?? null) : null;
                $region = $regionKey ? ($row[$regionKey] ?? null) : null;
                $city = $cityKey ? ($row[$cityKey] ?? null) : null;
                
                // Process custom fields
                $customFieldData = [];
                foreach ($this->columnMapping as $key => $columnHeader) {
                    if (strpos($key, 'custom_field_') === 0 && !empty($columnHeader)) {
                        $fieldName = str_replace(['custom_field_', '_column'], '', $key);
                        
                        // Convert header to Laravel Excel format
                        $convertedHeader = $this->convertHeaderToLaravelExcelFormat($columnHeader);
                        $customFieldValue = $row[$convertedHeader] ?? null;
                        
                        if (!empty($customFieldValue)) {
                            $customFieldData[$fieldName] = $customFieldValue;
                        }
                    }
                }
                
                Log::debug("Row $index mapped data", [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone_number' => $phone_number,
                    'email' => $email,
                    'company' => $company,
                    'date_of_birth' => $date_of_birth,
                    'gender' => $gender,
                    'country' => $country,
                    'region' => $region,
                    'city' => $city,
                    'custom_fields' => $customFieldData,
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
                    'date_of_birth' => $date_of_birth,
                    'gender' => $gender,
                    'country' => $country,
                    'region' => $region,
                    'city' => $city,
                    'custom_fields' => $customFieldData,
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
     * Convert header name to Laravel Excel format (snake_case, lowercase)
     * This matches how Laravel Excel processes headers with WithHeadingRow
     * 
     * @param string $header
     * @return string
     */
    private function convertHeaderToLaravelExcelFormat(string $header): string
    {
        // Convert to lowercase and replace spaces/special characters with underscores
        $converted = strtolower($header);
        $converted = preg_replace('/[^a-z0-9]+/i', '_', $converted);
        $converted = trim($converted, '_');
        
        Log::debug("Header conversion: '{$header}' -> '{$converted}'");
        
        return $converted;
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