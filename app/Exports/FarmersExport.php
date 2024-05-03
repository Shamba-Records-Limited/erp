<?php

namespace App\Exports;

use App\Farmer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmersExport implements FromCollection, WithMapping, WithHeadings
{
    private $farmers;

    public function __construct($farmers)
    {
        $this->farmers = $farmers;
    }

    public function collection()
    {
        return collect($this->farmers);
    }

    public function map($farmer): array
    {
        return [
            $farmer->user->first_name,
            $farmer->user->other_names,
            $farmer->user->email,
            $farmer->phone_no,
            $farmer->member_no,
            $farmer->id_no,
            $farmer->gender,
            $farmer->age,
            $farmer->country->name,
            $farmer->county,
            $farmer->location == null ? '' : $farmer->location->name,
            $farmer->route->name,
            $farmer->bank_account,
            $farmer->bank_account,
            $farmer->bank_branch->bank->name,
            $farmer->bank_branch->name,
            $farmer->customer_type,
            $farmer->kra,
            $farmer->farm_size,
        ];
    }

    public function headings(): array
    {
       return [
           'First Name',
           'Other Names',
           'Email',
           'Phone Number',
           'Member No.',
           'ID No',
           'Gender',
           'Age',
           'Country',
           'County',
           'Location',
           'Route',
           'Bank Account No',
           'Bank',
           'Bank Branch',
           'Farmer Type',
           'KRA',
           'Farm Size',
       ];
    }
}
