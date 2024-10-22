<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithMapping, WithHeadings
{
    private $orders;
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return collect($this->orders);
    }

    public function headings(): array
    {
        return [
            'Batch No',
            'Cooperative',
            'Delivery',
            'Status'
        ];
    }

    public function map($order): array
    {
        return [
            $order['batch_number'],
            $order['coop_name'],
            $order['delivery'],
            $order['status'],
        ];
    }
}
