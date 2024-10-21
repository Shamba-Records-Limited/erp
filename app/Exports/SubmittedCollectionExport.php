<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SubmittedCollectionExport implements FromCollection, WithMapping, WithHeadings
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
            'Unit',
            'Date',
            'Status'
        ];
    }

    public function map($item): array
    {
        return [
            $item->batch_no,
            $item->collection_number,
            ucwords(strtolower($item->farmer->user->first_name . ' ' . $item->farmer->user->other_names)),
            $item->product->name,
            $item->farmer->member_no,
            $item->collection_quality_standard != null ? $item->collection_quality_standard->name : 'Was Good',
            number_format($item->quantity),
            $item->product->unit->name,
           \Carbon\Carbon::create($item->date_collected)->format('Y-m-d, l').' '.config('enums.collection_time')[$item->collection_time],
            config('enums.collection_submission_statuses')[$item->submission_status]
        ];
    }
}
