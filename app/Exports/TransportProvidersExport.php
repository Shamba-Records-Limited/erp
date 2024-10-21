<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransportProvidersExport implements FromCollection, WithMapping, WithHeadings
{
    private $transportProviders;

    public function __construct($transportProviders)
    {
        $this->transportProviders = $transportProviders;
    }

    public function collection()
    {
        return collect($this->transportProviders);
    }

    public function map($transporter): array
    {
        return [
            $transporter->name,
            $transporter->phone_number,
            $transporter->location,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone Number',
            'Location'
        ];
    }
}