<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuotationExport implements FromCollection, WithMapping, WithHeadings
{
    private $saleQuotations;
    public function __construct($saleQuotations)
    {
        $this->saleQuotations = $saleQuotations;
    }

    public function collection()
    {
        return collect($this->saleQuotations);
    }

    public function headings(): array
    {
        return [
            'Quotation Number',
            'Customer Name',
            'Customer Email',
            'Number of Items',
            'Total Price',
            'Status',
            'Created At',
        ];
    }

    public function map($quotation): array
    {
        return [
            $quotation['quotation_number'],
            $quotation['customer_name'],
            $quotation['customer_email'],
            $quotation['items_count'],
            $quotation['total_price'],
            $quotation['status'],
            $quotation['created_at'],
        ];
    }
}
