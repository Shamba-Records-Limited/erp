<?php

namespace App\Exports;

use App\Loan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmerLoanExport implements FromCollection, WithMapping, WithHeadings
{
    private $farmer_id;

    public function __construct($farmer_id)
    {
        $this->farmer_id = $farmer_id;
    }

    public function collection()
    {
        $approved = Loan::STATUS_APPROVED;
        $loans = DB::select("SELECT l.id, l.balance, ls.type, l.due_date FROM loans l
                                    INNER JOIN loan_settings ls ON l.loan_setting_id = ls.id
                                    WHERE l.status = '$approved' AND l.balance > 0 AND  l.farmer_id = '$this->farmer_id'");

        return collect($loans);
    }

    public function headings(): array
    {
        return [
            'Loan ID',
            'Loan Type',
            'Balance',
            'Due Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->type,
            $row->balance,
            $row->due_date
        ];
    }
}
