<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VehiclesExport implements FromCollection, WithMapping, WithHeadings
{
    private $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function collection()
    {
        return collect($this->vehicles);
    }

    public function map($vehicle): array
    {
        return [
            $vehicle->registration_number,
            $vehicle->type ? $vehicle->type->name : '',
            $vehicle->driver ? ($vehicle->driver->first_name . ' ' . $vehicle->driver->other_names) : '',
            $vehicle->weight,
            $vehicle->statusText(),
            $vehicle->status_comment,
            $vehicle->status_date,
        ];
    }

    public function headings(): array
    {
        return [
            'Registration Number',
            'Vehicle Type',
            'Driver',
            'Vehicle Weight',
            'Status',
            'Status Comment',
            'Status Date'
        ];
    }
}