<?php

namespace App\Exports;

use App\Collection;
use Cache;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmerCollectionExport implements FromCollection, WithMapping, WithHeadings
{
    private $collections;

    public function __construct($collections)
    {
        $this->collections = $collections;
    }

    public function collection()
    {
        return $this->collections;
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Farmer',
            'Product',
            'Quantity',
            'Units',
            'Quality',
            'Date Collected',
            'Agent',
            'Comments',
            'Collection No',
            'Available Quantity'
        ];
    }

    public function map($collection): array
    {
        return [
            $collection->batch_no,
            ucwords(strtolower($collection->farmer->user->first_name . ' ' . $collection->farmer->user->other_names)),
            $collection->product->name,
            $collection->quantity,
            $collection->product->unit->name,
            $collection->collection_quality_standard != null ? $collection->collection_quality_standard->name : 'Was Good',
            $collection->date_collected,
            $collection->agent != null ? ucwords(strtolower($collection->agent->first_name . ' ' . $collection->agent->other_names)) : '',
            $collection->comments,
            $collection->collection_number,
            $collection->available_quantity
        ];
    }
}
