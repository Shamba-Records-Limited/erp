<?php

namespace App\Exports;

use App\Customer;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function map($customer): array
    {
        $isCompany = ($customer->customer_type == \App\Customer::CUSTOMER_TYPE_COMPANY);

        return [
            $customer->title." ".ucwords(strtolower($customer->name)),
            $customer->customer_type ? config('enums')["customer_types"][0][$customer->customer_type] : config('enums')["customer_types"][0][$customer->supplier_type],
            !$isCompany ? $customer->gender == "M" ? "Male": ($customer->gender == "F" ? "Female" : "Other") : "",
            $customer->email,
            '+254'.' '.substr($customer->phone_number, -9),
            $customer->location,
            $customer->address
        ];
    }
    public function headings(): array
    {
        return [
            'Name/Company',
            'Type',
            'Gender',
            'Email',
            'Phone',
            'Location',
            'Address'
        ];
    }
}
