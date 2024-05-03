<?php

namespace App\Exports;

use App\AccountingLedger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountingChartsOfAccountExport implements FromCollection, WithHeadings, WithMapping
{
    private $cooperativeId;

    public function __construct($cooperativeId)
    {
        $this->cooperativeId = $cooperativeId;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $ledgers = AccountingLedger::whereNull('deleted_at')
            ->where(
                function ($query) {
                    $query->where('cooperative_id', $this->cooperativeId)
                        ->orWhereNull('cooperative_id');
                }
            )->orderBy('parent_ledger_id')->orderBy('ledger_code')->get();
        return collect($ledgers);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Account Type',
            'Parent Ledger',
            'Ledger Code'
        ];
    }

    public function map($ledger): array
    {
        return [
            ucwords($ledger->name),
            ucwords($ledger->type),
            ucwords($ledger->parent_ledger->name),
            ucwords($ledger->ledger_code)
        ];
    }
}
