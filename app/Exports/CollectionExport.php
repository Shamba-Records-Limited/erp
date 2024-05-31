<?php

namespace App\Exports;

use App\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionExport implements FromCollection, WithMapping, WithHeadings
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
            'Coop Branch',
            'Batch No',
            'Farmer',
            'Product',
            'Quantity',
            'Unit',
            'Collection Time',
            'Collection Date'
        ];
    }

    public function map($collection): array
    {
        return [
            $collection->coop_branch->name,
            $collection->batch_no,
            $collection->farmer->user->username,
            $collection->product->name,
            $collection->quantity,
            $collection->unit->abbreviation,
            config("enums.collection_time")[$collection->collection_time],
            $collection->date_collected,
        ];
    }
}
