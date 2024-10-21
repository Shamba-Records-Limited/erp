<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SavingInstallmentExport implements  FromCollection, WithMapping, WithHeadings
{

    private $query;
    private $farmer;
    private $savingId;

    public function __construct($query, $farmer, $savingId)
    {
        $this->query = $query;
        $this->farmer = $farmer;
        $this->savingId = $savingId;
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
            'Amount',
            'Ref',
            'Date',
        ];
    }

    public function map($row): array
    {
        return [
            sprintf("%03d", $this->savingId),
            $this->farmer,
            number_format($row->amount),
            $row->reference,
            Carbon::parse($row->date)->format('D, d M Y  H:i:s')
        ];
    }
}
