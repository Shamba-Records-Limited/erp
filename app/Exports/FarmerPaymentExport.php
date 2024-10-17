<?php

namespace App\Exports;

use App\WalletTransaction;
use Cache;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FarmerPaymentExport implements FromCollection, WithHeadings, WithMapping
{
    private $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function headings(): array
    {
        return [
            'Type',
            'Reference',
            'Initiator',
            'Description',
            'Amount',
            'Date'
        ];
    }


    public function collection()
    {
        return $this->payments;
    }

    public function map($payment): array
    {
        return [
            ucwords(strtolower($payment->type)),
            $payment->reference,
            ucwords(strtolower($payment->initiator->first_name.' '.$payment->initiator->other_names)),
            $payment->description,
            $payment->amount,
            $payment->updated_at
        ];
    }
}
