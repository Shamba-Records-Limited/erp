<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MilledInventoryExport implements FromCollection, WithMapping, WithHeadings
{
    private $inventories;
    public function __construct($inventories)
    {
        $this->inventories = $inventories;
    }

    public function collection()
    {
        return collect($this->inventories);
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Lot No',
            'Quantity',
            'Milled Quantity',
            'Waste Quantity',
        ];
    }

    public function map($inventory): array
    {
        return [
            $inventory['batch_number'],
            $inventory['lot_number'],
            $inventory['quantity'],
            $inventory['milled_quantity'],
            $inventory['waste_quantity'],
        ];
    }
}
