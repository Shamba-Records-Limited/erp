<?php

namespace App\Exports;

use App\Cow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class FarmMngtApprovedLivestockExport implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    private $cooperativeId;

    public function __construct($cooperativeId)
    {
        $this->cooperativeId = $cooperativeId;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $animals = Cow::where('cooperative_id', $this->cooperativeId)->where('approval_status', Cow::APPROVAL_STATUS_APPROVED)->latest()->get();
        return collect($animals);
    }

    public function title(): string
    {
        return 'Approved Livestock and Poultry';
    }

    public function headings(): array
    {
        return [
            'Name',
            'Type',
            'Tag',
            'Breed',
            'Farmer'
        ];
    }

    public function map($cow): array
    {
        return [
            $cow->name,
            ucwords(strtolower($cow->animal_type)),
            $cow->tag_name,
            $cow->breed->name,
            ucwords(strtolower($cow->farmer->user->first_name).' '.strtolower($cow->farmer->user->other_names))
        ];
    }
}
