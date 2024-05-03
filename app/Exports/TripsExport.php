<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TripsExport implements FromCollection, WithMapping, WithHeadings
{
    private $trips;

    public function __construct($trips)
    {
        $this->trips = $trips;
    }

    public function collection()
    {
        return collect($this->trips);
    }

    public function map($trip): array
    {
        $dep = $trip->location('departure');
        $arr = $trip->location('arrival');
        $depWgt = $dep->weighBridgeEvent ? $dep->weighBridgeEvent->weight : '';
        $arrWgt = $dep->weighBridgeEvent ? $arr->weighBridgeEvent->weight : '';

        return [
            $trip->transport_type == 'OWN_VEHICLE' ? 'Company Vehicle' : ($trip->transportProvider ? $trip->transportProvider->name : ''),
            $trip->load_type,
            $dep->datetime,
            $dep->location ? $dep->location->name : '',
            $depWgt,
            $arr->datetime,
            $arr->location ? $arr->location->name : '',
            $arrWgt,
            $depWgt > 0 && $arrWgt > 0 ? ($depWgt - $arrWgt) : '--',
            $trip->trip_cost_total
        ];
    }

    public function headings(): array
    {
        return [
            'Transport Provider',
            'Load type',
            'Departure Date',
            'Departure Location',
            'Departure Load Weight',
            'Arrival Date',
            'Arrival Location',
            'Arrival Load Weight',
            'Discrepancy',
            'Trip Cost (Ksh)',
        ];
    }
}