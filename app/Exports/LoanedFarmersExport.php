<?php

namespace App\Exports;

use App\Loan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoanedFarmersExport implements FromCollection, WithMapping, WithHeadings
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
            'Loan ID',
            'Loan Status',
            'Farmer',
            'Loan Type',
            'Amount',
            'Balance',
            'Due Date',
        ];
    }

    public function map($row): array
    {
        return [
            sprintf("%03d", $row->id),
            $row->status == Loan::STATUS_APPROVED ? 'Approved':
                ($row->status == Loan::STATUS_REPAID ? 'Repaid' :
                    ($row->status == Loan::STATUS_PARTIAL_REPAYMENT ? 'Partially Paid' : 'Bought Off')),
            ucwords(strtolower($row->first_name.' '.$row->other_names)),
            $row->type,
            $row->amount,
            $row->balance,
            $row->due_date,
        ];
    }
}
