<?php

namespace App\Exports;

use App\Crop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmCropExport implements FromCollection, WithHeadings, WithMapping

{
    private $cooperative_id;

    public function __construct($cooperative_id)
    {
        $this->cooperative_id = $cooperative_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return Crop::crops($this->cooperative_id);
    }

    public function map($crop): array
    {
        return [
            $crop->product_id ? $crop->product->name : '-',
            $crop->variety,
            $crop->expected_yields.' '.($crop->farm_unit_id ? $crop->farm_unit->name : ''),
            $crop->recommended_areas,
            $crop->description,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Variety',
            'Farm Unit',
            'Recommended Areas',
            'Description'
        ];
    }


}
