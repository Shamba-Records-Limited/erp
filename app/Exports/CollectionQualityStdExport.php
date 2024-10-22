<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CollectionQualityStdExport implements  FromCollection,WithMapping, WithHeadings
{
    private $standards;
    public function __construct($standards)
{
    $this->standards = $standards;
}

    public function collection()
{
    return collect($this->standards);
}

    public function headings(): array
{
    return [
        'Name',
    ];
}

    public function map($std): array
{
    return [
        $std->name,
    ];
}
}
