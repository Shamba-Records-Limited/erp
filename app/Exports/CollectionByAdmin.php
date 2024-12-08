<?php

namespace App\Exports;

use App\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionByAdmin implements FromCollection, WithMapping, WithHeadings
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
            'Cooperative',
            'Collection No',
            'Lot No',
            'Farmer',
            'Product',
            'Qty',
            'Unit'
        ];
    }
    public function map($collection): array
    {
        return [
            $collection['cooperative_name'],
            $collection['collection_number'],
            $collection['lot_number'],
            $collection['first_name'],
            $collection['product_name'],
            $collection['quantity'],
            $collection['unit'],
        ];
    }
}
