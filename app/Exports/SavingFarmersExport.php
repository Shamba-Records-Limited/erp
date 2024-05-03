<?php

namespace App\Exports;

use App\SavingAccount;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SavingFarmersExport implements FromCollection, WithMapping, WithHeadings
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
            'Saving ID',
            'Farmer',
            'Saving Type',
            'Amount',
            'Interest Rate',
            'Interest',
            'Amount + Interest',
            'Date Started',
            'Maturity Date',
            'Saving Status'
        ];
    }

    public function map($row): array
    {
        return [
            sprintf("%03d", $row->id),
            ucwords(strtolower($row->first_name.' '.$row->other_names)),
            $row->saving_type,
            number_format($row->amount),
            $row->interest_rate,
            number_format(($row->amount*$row->interest_rate)/ 100),
            number_format((($row->amount*$row->interest_rate)/ 100) +  $row->amount),
            $row->date_started,
            $row->maturity_date,
            $row->status == SavingAccount::STATUS_ACTIVE ? 'Active':  'Withdrawn',
        ];
    }
}
