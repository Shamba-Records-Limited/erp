<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JsonExcelDataExport implements FromCollection , WithHeadings
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    // This method converts the data to a collection
    public function collection()
    {
        return collect($this->data);
    }
    // This method returns the headings (already included in the data)
    public function headings(): array
    {
        // Use the first row of data (headers) as the headings
        return !empty($this->data) && is_array($this->data[0]) ? array_keys($this->data[0]) : [];
    }
}
