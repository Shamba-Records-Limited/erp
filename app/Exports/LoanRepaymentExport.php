<?php

namespace App\Exports;

use App\LoanInstallment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoanRepaymentExport implements FromCollection, WithMapping, WithHeadings
{

    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }


    public function collection()
    {
        $loans = DB::select($this->query);
        return collect($loans);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone Number',
            'Loan ID',
            'Loan Type',
            'Principle Amount',
            'Interest Amount',
            'Interest + Amount',
            'Installment Amount',
            'Loan Balance',
            'Due Date',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            ucwords(strtolower($row->first_name.' '.$row->other_names)),
            $row->phone_no,
            sprintf("%03d", $row->id),
            $row->type,
            number_format($row->principle),
            ($row->amount * $row->principle)/100,
            (($row->amount * $row->principle)/100) + $row->principle,
            $row->installment,
            $row->balance,
            Carbon::parse($row->installment_date)->format('d-m-Y'),
            $row->status == LoanInstallment::STATUS_PENDING ? 'Pending':
                ($row->status == LoanInstallment::STATUS_PAID ? 'Paid' : 'Partial Paid'),
        ];
    }
}
