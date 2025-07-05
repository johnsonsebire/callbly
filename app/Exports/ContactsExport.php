<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;


class ContactsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $contacts;
    protected $headers;
    protected $columns;
    protected $customFields;

    /**
     * @param \Illuminate\Support\Collection $contacts
     * @param array $headers
     * @param \Illuminate\Support\Collection $customFields
     */
    public function __construct($contacts, array $headers, $customFields = null)
    {
        $this->contacts = $contacts;
        $this->headers = $headers;
        $this->columns = array_keys($headers);
        $this->customFields = $customFields ?? collect();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->contacts;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return array_values($this->headers);
    }

    /**
     * @param mixed $contact
     * @return array
     */
    public function map($contact): array
    {
        $row = [];
        
        foreach ($this->columns as $column) {
            if (strpos($column, 'custom_field_') === 0) {
                // Handle custom fields
                $fieldName = str_replace('custom_field_', '', $column);
                $customFieldValue = $contact->custom_fields[$fieldName] ?? '';
                
                // Format the value based on field type if we have the field definition
                $customField = $this->customFields->firstWhere('name', $fieldName);
                if ($customField) {
                    if ($customField->type === 'checkbox') {
                        $customFieldValue = $customFieldValue ? 'Yes' : 'No';
                    } elseif ($customField->type === 'date' && $customFieldValue) {
                        $customFieldValue = \Carbon\Carbon::parse($customFieldValue)->format('Y-m-d');
                    }
                }
                
                $row[] = $customFieldValue;
            } else {
                // Handle regular contact fields
                $row[] = $contact->{$column} ?? '';
            }
        }
        
        return $row;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}