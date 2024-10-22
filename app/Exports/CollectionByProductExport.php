<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionByProductExport implements FromCollection,WithMapping, WithHeadings
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
            'Batch No.',
            'Collection ID',
            'Farmer',
            'Member No.',
            'Product',
            'Quality',
            'Quantity',
            'Available Quantity',
            'Unit',
            'Date',
            'Agent'
        ];
    }

    public function map($item): array
    {
        return [
            $item->batch_no,
            $item->collection_number,
            ucwords(strtolower($item->farmer->user->first_name.' '.$item->farmer->user->other_names)),
            $item->farmer->member_no,
            $item->product->name,
            $item->collection_quality_standard != null ? $item->collection_quality_standard->name : 'Was Good',
            number_format($item->quantity),
            number_format($item->available_quantity),
            $item->product->unit->name,
            \Carbon\Carbon::create($item->date_collected)->format('Y-m-d'),
            $item->agent ? ucwords(strtolower($item->agent->first_name.' '.$item->agent->other_names)): ''
        ];
    }
}
