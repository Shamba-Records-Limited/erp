<?php

namespace App\Exports;

use App\SaleItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchaseExport implements FromCollection, WithHeadings, WithMapping
{

    private $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function collection()
    {
        return collect($this->purchases);
    }

    public function headings(): array
    {
        return [
            'Sale Batch #',
            'Amount',
            'Discount',
            'Total Payable',
            'Paid Amount',
            'Balance',
            'Returns',
            'Status',
            'Date'
        ];
    }

    public function map($p): array
    {
        return [
            $p->sale_batch_number,
            $p->amount,
            $p->discount,
            $p->amount - $p->discount,
            $p->paid_amount,
            $p->balance + $p->returns_value,
            $p->returns_value,
            config('enums.collection_submission_statuses')[$p->status],
            $p->date
        ];
    }
}
