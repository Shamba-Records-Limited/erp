<?php

namespace App\Exports;

use App\LoanInstallment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoanInstallmentExport implements FromCollection, WithMapping, WithHeadings
{

    private $farmer;
    private $loanId;

    public function __construct($farmer, $loanId)
    {
        $this->farmer = $farmer;
        $this->loanId = $loanId;
    }


    public function collection()
    {
        return  LoanInstallment::where('loan_id', $this->loanId)->get();
    }

    public function headings(): array
    {
        return [
            'Loan ID',
            'Farmer',
            'Amount',
            'Date',
            'Status'
        ];
    }

    public function map($row): array
    {
        return [
            sprintf("%03d", $this->loanId),
            $this->farmer,
            number_format($row->amount),
            $row->date,
            $row->status== LoanInstallment::STATUS_PENDING ? 'Pending' :
                ( $row->status == LoanInstallment:: STATUS_PAID ? 'Paid' :
                    'Partially Paid')
        ];
    }
}
