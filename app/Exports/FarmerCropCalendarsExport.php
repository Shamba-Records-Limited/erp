<?php

namespace App\Exports;

use App\FarmerCrop;
use App\User;

use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class FarmerCropCalendarsExport implements FromCollection, WithMapping, WithHeadings, WithTitle
{
    private User $user;
    /**
     * @return User $user
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
        $farmer_crops = FarmerCrop::farmerCrops($this->user, 1);
        return collect($farmer_crops);
    }

    public function map($row): array
    {
            return [
                ucwords(strtolower($row->farmer->user->first_name) . ' ' . strtolower($row->farmer->user->other_names)),
                $row->crop->product_id ? ucwords(strtolower($row->crop->product->name)) : '-',
                ucwords(strtolower($row->crop->variety)),
                ucwords(strtolower($row->stage->name)),
                $row->start_date,
                $row->last_date,
                $row->next_stage ? ucwords(strtolower($row->next_stage->name)) : '-',
                $row->total_cost
            ];
    }

    public function headings(): array
    {
        return [
            'Farmer',
            'Crop',
            'Crop Variety',
            'Current Stage',
            'Start Date',
            'Last Date',
            'Next Stage',
            'Total Cost'
        ];
    }

    public function title(): string
    {
        return 'Farmer Crop Calender';
    }
}
