<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PreMilledInventoryExport implements FromCollection, WithMapping, WithHeadings
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
            'Inventory No',
            'Batch No',
            'Lot No',
            'Quantity'
        ];
    }

    public function map($inventory): array
    {
        return [
            $inventory['inventory_number'],
            $inventory['batch_number'],
            $inventory['lot_number'],
            $inventory['quantity'],
        ];
    }
}
