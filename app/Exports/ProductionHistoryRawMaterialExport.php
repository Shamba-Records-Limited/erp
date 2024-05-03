<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductionHistoryRawMaterialExport implements FromCollection, WithMapping, WithHeadings
{
    private $productions;

    public function __construct($productions){
        $this->productions = $productions;
    }
    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->productions);
    }

    public function headings(): array
    {
        return [
            'Production Lot',
            'Raw Material',
            'Quantity',
            'Cost',
            'Total Cost',
        ];
    }

    public function map($production): array
    {
        return [
            $production->productionHistory->production_lot,
            $production->rawMaterial->name,
            $production->quantity,
            $production->cost,
            $production->cost*$production->quantity
        ];
    }
}
