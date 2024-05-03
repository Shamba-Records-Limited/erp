<?php

namespace App\Exports;

use App\ReportedCase;
use App\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportedCasesExport implements FromCollection,WithMapping,WithHeadings
{
    private User $user;

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
        $reported_cases = ReportedCase::cases($this->user);
        return collect($reported_cases);
    }

    public function map($case): array
    {
        return [
            ucwords(strtolower($case->farmer->first_name.' '.$case->farmer->other_names)),
            $case->disease->name.' ('.$case->disease->disease_category->name.')',
            $case->symptoms,
            $case->status,
            $case->booked ? 'Yes' : 'No',
        ];
    }
    public function headings(): array
    {
        return [
            'Farmer',
            'Disease',
            'Symptoms',
            'Status',
            'Booked',
        ];
    }
}
