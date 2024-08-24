<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromCollection, WithMapping, WithHeadings
{
    private $transactions;
    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return collect($this->transactions);
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Lot No',
            'Quantity',
            'Milled Quantity',
            'Waste Quantity',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction['transaction_number'],
            $transaction['subject'],
            $transaction['sender'],
            $transaction['recipient'],
            $transaction['amount'],
            $transaction['status'],
        ];
    }
}
