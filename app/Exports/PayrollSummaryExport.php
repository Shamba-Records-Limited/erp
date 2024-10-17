<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayrollSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    private $payrolls;

    public function __construct($payrolls)
    {
        $this->payrolls = $payrolls;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->payrolls);
    }

    public function map($payroll): array
    {
        return [
            ucwords(strtolower($payroll->name)),
            $payroll->department,
            config('enums.Months')[$payroll->period_month].', '.$payroll->period_year,
            number_format($payroll->basic_pay,2),
            number_format($payroll->taxable_income,2),
            number_format($payroll->paye,2),
            number_format($payroll->net_pay,2)
        ];
    }

    public function headings(): array
    {
        return [
            'Employee',
            'Department',
            'Month',
            'Basic Pay',
            'Taxable Income',
            'P.A.Y.E',
            'Net Pay'
        ];
    }
}
