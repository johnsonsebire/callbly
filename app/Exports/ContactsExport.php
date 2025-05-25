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

    /**
     * @param \Illuminate\Support\Collection $contacts
     * @param array $headers
     */
    public function __construct($contacts, array $headers)
    {
        $this->contacts = $contacts;
        $this->headers = $headers;
        $this->columns = array_keys($headers);
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
            $row[] = $contact->{$column} ?? '';
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