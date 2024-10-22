<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingSupplyExport implements FromCollection, WithMapping, WithHeadings
{
    private $supplies;

    public function __construct($supplies){
        $this->supplies = $supplies;
    }
    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->supplies);
    }

    public function headings(): array
    {
        return [
            'Raw Material',
            'Available Quantity',
            'Supply Count',
        ];
    }

    public function map($supply): array
    {
       return [
           $supply->name,
           $supply->quantity,
           $supply->total_count
       ];
    }
}
