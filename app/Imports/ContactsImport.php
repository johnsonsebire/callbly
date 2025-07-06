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
        'errors' => 0,
        'error_details' => [] // Store detailed error information
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
            // Initialize contact data for error reporting (accessible throughout the loop)
            $contactDataForErrors = [
                'first_name' => '',
                'last_name' => '',
                'phone_number' => '',
                'email' => '',
                'company' => '',
                'date_of_birth' => '',
                'gender' => '',
                'country' => '',
                'region' => '',
                'city' => '',
                'custom_fields' => []
            ];
            
            try {
                // Make sure we're not trying to process an empty row
                if ($row->isEmpty()) {
                    Log::info('Skipping empty row at index ' . $index);
                    continue;
                }
                
                // Get the numeric index or column name from the mapping
                // Note: Laravel Excel with WithHeadingRow converts headers to snake_case and lowercase
                $firstNameKey = $this->convertHeaderToLaravelExcelFormat($this->columnMapping['first_name']);
                $lastNameKey = isset($this->columnMapping['last_name']) && !empty($this->columnMapping['last_name']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['last_name']) : null;
                $phoneKey = $this->convertHeaderToLaravelExcelFormat($this->columnMapping['phone']);
                $emailKey = isset($this->columnMapping['email']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['email']) : null;
                $companyKey = isset($this->columnMapping['company']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['company']) : null;
                $dateOfBirthKey = isset($this->columnMapping['date_of_birth']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['date_of_birth']) : null;
                $genderKey = isset($this->columnMapping['gender']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['gender']) : null;
                $countryKey = isset($this->columnMapping['country']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['country']) : null;
                $regionKey = isset($this->columnMapping['region']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['region']) : null;
                $cityKey = isset($this->columnMapping['city']) ? $this->convertHeaderToLaravelExcelFormat($this->columnMapping['city']) : null;
                
                Log::debug("Row keys: " . json_encode($row->keys()->toArray()));
                Log::debug("Original mapping keys: first_name={$this->columnMapping['first_name']}, last_name=" . ($this->columnMapping['last_name'] ?? 'null') . ", phone={$this->columnMapping['phone']}");
                Log::debug("Converted mapping keys: first_name={$firstNameKey}, last_name=" . ($lastNameKey ?? 'null') . ", phone={$phoneKey}");
                
                // Access data directly from the row using Laravel Excel converted keys
                Log::debug("Attempting to access: firstNameKey={$firstNameKey}, lastNameKey=" . ($lastNameKey ?? 'null') . ", phoneKey={$phoneKey}");
                
                $first_name = $row[$firstNameKey] ?? null;
                $last_name = $lastNameKey ? ($row[$lastNameKey] ?? null) : null;
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
                
                // Update contact data for error reporting with actual values
                $contactDataForErrors = [
                    'first_name' => $first_name ?? '',
                    'last_name' => $last_name ?? '',
                    'phone_number' => $phone_number ?? '',
                    'email' => $email ?? '',
                    'company' => $company ?? '',
                    'date_of_birth' => $date_of_birth ?? '',
                    'gender' => $gender ?? '',
                    'country' => $country ?? '',
                    'region' => $region ?? '',
                    'city' => $city ?? '',
                    'custom_fields' => $customFieldData
                ];
                
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
                    $this->results['error_details'][] = [
                        'row_number' => $index + 2, // +2 because index is 0-based and we skip header row
                        'contact_data' => $contactDataForErrors,
                        'error_type' => 'Validation Error',
                        'error_message' => 'Phone number is required'
                    ];
                    Log::warning("Row $index skipped: No phone number");
                    continue;
                }
                
                // Check for duplicate
                $existingContact = Contact::where('user_id', Auth::id())
                    ->where('phone_number', $phone_number)
                    ->first();
                    
                if ($existingContact) {
                    $this->results['duplicates']++;
                    $this->results['error_details'][] = [
                        'row_number' => $index + 2,
                        'contact_data' => $contactDataForErrors,
                        'error_type' => 'Duplicate',
                        'error_message' => 'Contact with this phone number already exists'
                    ];
                    Log::info("Row $index skipped: Duplicate phone number $phone_number");
                    continue;
                }
                
                // Create new contact
                $contactData = [
                    'user_id' => Auth::id(),
                    'first_name' => $first_name,
                    'phone_number' => $phone_number,
                    'custom_fields' => $customFieldData,
                ];
                
                // Only add non-null values to avoid database constraint issues
                if (!empty($last_name)) {
                    $contactData['last_name'] = $last_name;
                }
                if (!empty($email)) {
                    $contactData['email'] = $email;
                }
                if (!empty($company)) {
                    $contactData['company'] = $company;
                }
                if (!empty($date_of_birth)) {
                    $contactData['date_of_birth'] = $date_of_birth;
                }
                if (!empty($gender)) {
                    $contactData['gender'] = $gender;
                }
                if (!empty($country)) {
                    $contactData['country'] = $country;
                }
                if (!empty($region)) {
                    $contactData['region'] = $region;
                }
                if (!empty($city)) {
                    $contactData['city'] = $city;
                }
                
                $contact = new Contact($contactData);
                
                $contact->save();
                $this->results['imported']++;
                
                // Add to group if specified
                if ($this->groupId) {
                    $contact->groups()->attach($this->groupId);
                    Log::info("Contact with phone $phone_number added to group $this->groupId");
                }
                
            } catch (\Exception $e) {
                $this->results['errors']++;
                
                // Capture detailed error information
                $errorDetail = [
                    'row_number' => $index + 2, // +2 because index is 0-based and we skip header row
                    'contact_data' => $contactDataForErrors,
                    'error_message' => $e->getMessage(),
                    'error_type' => get_class($e)
                ];
                
                $this->results['error_details'][] = $errorDetail;
                
                Log::error("Error importing row $index: " . $e->getMessage());
                Log::error("Contact data: " . json_encode($errorDetail['contact_data']));
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