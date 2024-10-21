<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdvanceDeductionTransactionExport implements FromCollection, WithHeadings, WithMapping
{
    private $data;

    public function __construct($data){
        $this->data = $data;
    }
    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
       return [
           'Payroll Period',
           'Amount',
           'Balance'
       ];
    }

    public function map($trx): array
    {
       return [
           config('enums.Months')[$trx->month].' '.$trx->year,
           number_format($trx->amount,2),
           number_format($trx->balance,2)
       ];
    }
}
