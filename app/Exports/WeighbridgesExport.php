<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WeighbridgesExport implements FromCollection, WithMapping, WithHeadings
{
    private $weighbridges;

    public function __construct($weighbridges)
    {
        $this->weighbridges = $weighbridges;
    }

    public function collection()
    {
        return collect($this->weighbridges);
    }

    public function map($weighbridge): array
    {
        return [
            $weighbridge->code,
            $weighbridge->max_weight,
            $weighbridge->location ? $weighbridge->location->name : '',
            $weighbridge->statusText(),
            $weighbridge->status_comment,
            $weighbridge->status_date,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Weight Limit (KGS)',
            'Location',
            'Status',
            'Status Comment',
            'Registration Date',
        ];
    }
}