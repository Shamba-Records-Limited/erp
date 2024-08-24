<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoiceExport implements FromCollection, WithMapping, WithHeadings
{
    private $invoices;
    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        return collect($this->invoices);
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer Name',
            'Customer Email',
            'Number of Items',
            'Total Price',
            'Status',
            'Created At',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice['invoice_number'],
            $invoice['customer_name'],
            $invoice['customer_email'],
            $invoice['items_count'],
            $invoice['total_price'],
            $invoice['status'],
            $invoice['created_at'],
        ];
    }
}
