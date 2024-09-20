<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GeneratableExport implements FromCollection, WithMapping, WithHeadings
{
    private $rows;
    private $columnDefinitions;
    public function __construct($columnDefinitions, $rows)
    {
        $this->columnDefinitions = $columnDefinitions;
        $this->rows = $rows;
    }

    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        $headings = [];
        foreach ($this->columnDefinitions as $colDefinition) {
            $headings[] = $colDefinition['name'];
        }

        return $headings;
    }

    public function map($row): array
    {
        $mappedRow = [];
        $row = (array)$row;
        foreach ($this->columnDefinitions as $colDefinition) {
            $key = $colDefinition['key'];
            $mappedRow[] = $row[$key];
        } 
        return $mappedRow;
    }
}
