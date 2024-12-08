<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromCollection, WithMapping, WithHeadings
{
    private $sales;
    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return collect($this->sales);
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Sale Amount',
        ];
    }

    public function map($sales): array
    {
        return [
            $sales->sale_batch_number,
            $sales->paid_amount,
        ];
    }
}
