<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WarehouseExport implements FromCollection, WithMapping, WithHeadings
{
    private $warehouses;
    public function __construct($warehouses)
    {
        $this->warehouses = $warehouses;
    }

    public function collection()
    {
        return collect($this->warehouses);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Location',
        ];
    }

    public function map($warehouse): array
    {
        return [
            $warehouse->name,
            $warehouse->location,
        ];
    }
}
