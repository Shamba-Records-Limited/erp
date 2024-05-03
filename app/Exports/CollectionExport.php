<?php

namespace App\Exports;

use App\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionExport implements FromCollection,WithMapping, WithHeadings
{
    private $collections;
    public function __construct($collections)
    {
        $this->collections = $collections;
    }

    public function collection()
    {
        return collect($this->collections);
    }

    public function headings(): array
    {
       return [
           'Product',
           'Buying Price',
           'Quantity Supplied',
           'Unit',
           'Total Value',
           'Available Quantity',
           'Available Total Value'
       ];
    }

    public function map($collection): array
    {
        return [
            $collection->name,
            $collection->buying_price,
            $collection->quantity,
            $collection->unit,
            $collection->total_value,
            $collection->available_quantity,
            $collection->available_quantity_value,
        ];
    }
}
