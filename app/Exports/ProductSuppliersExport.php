<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductSuppliersExport implements FromCollection, WithHeadings, WithMapping
{
    private $suppliers;

    public function __construct($suppliers)
    {
        $this->suppliers = $suppliers;
    }

    public function collection()
    {
        return collect($this->suppliers);
    }

    public function map($farmer): array
    {
        return [
            ucwords(strtolower($farmer->name)),
            $farmer->route,
            $farmer->member_no,
            $farmer->id_no,
            $farmer->phone_no,
            config('enums.farmer_customer_types')[$farmer->customer_type]
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Route',
            'Member No.',
            'Id/Passport No.',
            'Phone No.',
            'Customer Type'
        ];
    }
}
