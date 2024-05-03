<?php

namespace App\Exports;

use App\Loan;
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InterestExport implements FromCollection, WithMapping, WithHeadings
{
    private $cooperative;
    
    /**
     * @param $cooperative
     */

    public function __construct($cooperative)
    {
        $this->cooperative = $cooperative;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $interests = Loan::interests($this->cooperative);
        return collect($interests);
    }

    public function map($row): array
    {
        if (Carbon::parse($row->due_date)->lt(Carbon::now())) {
            $penalty = $row->amount * ($row->penalty/100);
        } else {
            $penalty = 0;
        }
        return [

            $row->id,
            $row->first_name.' '.$row->other_names,
            number_format($row->amount, 2, '.', ','),
            number_format($row->amount*($row->interest/100), 2, '.', ','),
            number_format($penalty, 2, '.',','),
            Carbon::parse($row->due_date)->format('Y, M, d')
        ];
    }

    public function headings(): array
    {
        return [
            'Loan ID',
            'Farmer',
            'Amount',
            'Interest',
            'Penalty',
            'Due Date'
        ];
    }

}
