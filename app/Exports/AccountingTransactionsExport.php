<?php

namespace App\Exports;

use App\AccountingTransaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountingTransactionsExport implements FromCollection, WithMapping, WithHeadings
{
    private $cooperative;

    public function __construct($cooperative)
    {
        $this->cooperative = $cooperative;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return AccountingTransaction::accountingTransactions($this->cooperative);
    }

    public function map($row): array
    {
        return [
            $row->ref_no,
            $row->ledger,
            ucwords($row->ledger_type) . ' ' . $row->account_type,
            $row->credit ? number_format($row->credit, 2, '.', ',') : '-',
            $row->debit ? number_format($row->debit, 2, '.', ',') : '-',
            Carbon::parse($row->date)->format('Y M, d'),
            $row->particulars
        ];
    }

    public function headings(): array
    {
        return [
            'Reference Number',
            'Ledger',
            'Type',
            'Credit',
            'Debit',
            'Date',
            'Particulars'
        ];
    }
}
