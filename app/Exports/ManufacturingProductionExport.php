<?php

namespace App\Exports;

use App\Production;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingProductionExport implements FromCollection, WithMapping, WithHeadings
{
    private $productions;

    public function __construct($productions)
    {
        $this->productions = $productions;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->productions);
    }

    public function map($prod): array
    {
        return [
            $prod->product,
            number_format($prod->available_quantity,2),
            $prod->units,
            $prod->final_selling_price,
            number_format($prod->final_selling_price * $prod->available_quantity,2),
            number_format($prod->production_cost,2),
            number_format(($prod->final_selling_price - $prod->production_cost), 2),
            $prod->store
        ];
    }
    public function headings(): array
    {
        return [
            'Product',
            'Available Quantity',
            'Units',
            'Unit Selling Price',
            'Value',
            'Unit Production Cost',
            'Margin',
            'Store'
        ];
    }
}
