<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BulkPaymentFarmers implements FromCollection, WithMapping, WithHeadings
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
            'Bank.',
            'Account No.',
            'Payment Mode',
            'Internal Ref',
            'Amount'
        ];
    }

    public function map($item): array
    {
        return [
            ucwords(strtolower($item->name)),
            $item->member_no,
            $item->phone_no,
            $item->bank.', '.$item->branch,
            $item->bank_account,
            config('enums.bulk_payment_modes')[$item->mode],
            $item->reference,
            $item->amount,
        ];
    }

}
