<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DefaultedLoanExport implements FromCollection, WithMapping, WithHeadings
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
            'Loan Amount',
            'Installment Amount',
            'Loan Balance',
            'Due Date',
        ];
    }

    public function map($row): array
    {
        return [
            ucwords(strtolower($row->first_name.' '.$row->other_names)),
            $row->phone_no,
            sprintf("%03d", $row->id),
            $row->type,
            number_format($row->amount),
            $row->installment,
            $row->balance,
            Carbon::parse($row->installment_date)->format('d-m-Y'),
        ];
    }
}
