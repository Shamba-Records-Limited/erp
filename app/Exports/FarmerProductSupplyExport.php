<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmerProductSupplyExport implements FromCollection, WithHeadings, WithMapping
{
    private $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return collect($this->products);
    }

    public function map($row): array
    {
        return [
            $row->product_name,
            $row->category,
            $row->unit,
            $row->total_quantity,
            $row->unit_cost,
            $row->total_cost
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Category',
            'Unit Measure',
            'Quantity Supplied',
            'Buying Price',
            'Total Value',
        ];
    }
}
