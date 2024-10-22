<?php

namespace App\Exports;

use App\CoopEmployee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    private $employees;
    public function __construct($employees)
    {
        $this->employees = $employees;
    }


    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->employees);
    }

    public function map($employee): array
    {
        return [
            $employee->name,
            $employee->country,
            $employee->employee_no,
            $employee->id_no,
            $employee->phone_no,
            $employee->employment_type,
            $employee->position,
             config('enums.employment_status')[$employee->status],
        ];
    }

    public function headings(): array
    {
       return [
           'Name',
           'Country',
           'Employee No.',
           'ID No',
           'Phone Number',
           'Employment Type',
           'Position',
           'Status',
       ];
    }
}
