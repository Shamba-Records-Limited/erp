<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProcessedBulkPaymentExport implements FromCollection, WithMapping, WithHeadings
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
            'Batch',
            'Initiated By',
            'Payment Mode.',
            'Amount',
            'Date',
            'Date Completed',
            'Status'
        ];
    }

    public function map($item): array
    {
        return [
            $item->batch,
            ucwords(strtolower($item->names)),
            config('enums.bulk_payment_modes')[$item->mode],
            $item->total_amount,
            \Carbon\Carbon::parse($item->date)->format('d F, Y'),
            \Carbon\Carbon::parse($item->date_updated)->format('d F, Y'),
            config('enums.bulk_payment_status')[$item->status]
        ];
    }

}
