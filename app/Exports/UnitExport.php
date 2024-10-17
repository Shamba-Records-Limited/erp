<?php

namespace App\Exports;

use App\Unit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UnitExport implements FromCollection,WithMapping, WithHeadings
{
    private $cooperative;

    public function __construct($cooperative)
    {
        $this->cooperative = $cooperative;
    }


    public function collection()
    {
        return Unit::units($this->cooperative);
    }

    public function headings(): array
    {
        return [
            "Unit Name",
            "Date Created"
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            Carbon::parse($row->created_at)->format('Y, M, d')
        ];
    }
}
