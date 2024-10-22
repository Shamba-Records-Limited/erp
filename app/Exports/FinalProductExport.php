<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinalProductExport implements FromCollection, WithMapping, WithHeadings
{
    private $finalProducts;
    public function __construct($finalProducts)
    {
        $this->finalProducts = $finalProducts;
    }

    public function collection()
    {
        return collect($this->finalProducts);
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

    public function map($finalProduct): array
    {
        return [
            $finalProduct['product_number'],
            $finalProduct['name'],
            $finalProduct['quantity'],
            $finalProduct['selling_price'],
            $finalProduct['count'],
        ];
    }
}
