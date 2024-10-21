<?php

namespace App\Exports;

use App\FarmerCrop;
use App\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class FarmerLivestockorPoultryCalExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    private User $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
        $farmer_crops = FarmerCrop::farmerCrops($this->user, 2);
        return collect($farmer_crops);
    }

    public function headings(): array
    {
        return [
            'Farmer',
            'Livestock',
            'Breed',
            'Current Stage',
            'Start Date',
            'Last Date',
            'Next Stage',
            'Total Cost'
        ];
    }

    public function map($row): array
    {
            return [
                ucwords(strtolower($row->farmer->user->first_name) . ' ' . strtolower($row->farmer->user->other_names)),
                ucwords(strtolower($row->livestock->name . ', ' . $row->livestock->animal_type)),
                ucwords(strtolower($row->livestock->breed->name)),
                ucwords(strtolower($row->stage->name)),
                $row->start_date,
                $row->last_date,
                $row->next_stage ? ucwords(strtolower($row->next_stage->name)) : '-',
                $row->total_cost
            ];
    }

    public function title(): string
    {
        return 'Farmer Livestock/Poultry Calendars';
    }
}
