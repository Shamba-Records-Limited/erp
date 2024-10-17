<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BulkPaymentExport implements FromCollection, WithMapping, WithHeadings
{
    private $pending_payments;

    public function __construct($pending_payments)
    {
        $this->pending_payments = $pending_payments;
    }

    public function collection()
    {
        return collect($this->pending_payments);
    }

    public function headings(): array
    {
        return [
            'Farmer',
            'Member No.',
            'Phone No.',
            'Account No.',
            'Bank.',
            'Collection Worth',
            'Pending Payments',
        ];
    }

    public function map($item): array
    {
        return [
            ucwords(strtolower($item->name)),
            $item->member_no,
            $item->phone_no,
            $item->bank_account,
            $item->bank.' '.$item->branch,
            $item->collection_worth,
            $item->pending_payments,
        ];
    }

}
