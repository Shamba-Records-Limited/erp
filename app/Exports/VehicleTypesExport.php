<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehicleTypesExport implements FromCollection, WithMapping, WithHeadings
{
    private $vehicleTypes;

    public function __construct($vehicleTypes)
    {
        $this->vehicleTypes = $vehicleTypes;
    }

    public function collection()
    {
        return collect($this->vehicleTypes);
    }

    public function map($vehicleType): array
    {
        return [
            $vehicleType->name,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
        ];
    }
}