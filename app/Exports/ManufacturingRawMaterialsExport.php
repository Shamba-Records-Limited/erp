<?php

namespace App\Exports;

use App\RawMaterial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingRawMaterialsExport implements FromCollection, WithHeadings, WithMapping
{
    private $materials;

    public function __construct($materials)
    {
        $this->materials = $materials;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->materials);
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Estimate Cost',
            'Units'
        ];
    }

    public function map($material): array
    {
        return [
            $material->name,
            number_format($material->estimated_cost,2,'.',','),
            $material->unit->name
        ];
    }
}
