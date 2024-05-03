<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReturnExport implements FromCollection, WithHeadings, WithMapping
{
    private $sales_return;

    public function __construct($sales_return)
    {
        $this->sales_return = $sales_return;
    }

    public function collection()
    {
        return $this->sales_return;
    }

    public function headings(): array
    {
        return [
            'Invoice No.',
            'Customer',
            'Product',
            'Quantity',
            'Amount',
            'Notes',
            'Date',
            'Served By',
        ];
    }

    public function map($item): array
    {
        $itemName = $item->manufactured_product_id ? $item->manufactured_product->finalProduct->name : $item->collection->product->name;
        $date = \Carbon\Carbon::parse($item->date);
       return [
           $item->sale->invoices->invoice_number.'-'.$item->sale->invoices->invoice_count,
           $item->sale->farmer_id ? '(Farmer) '.(ucwords(strtolower($item->sale->farmer->user->first_name.' '.$item->sale->farmer->user->other_names))) : $item->sale->customer->name,
           $itemName,
           $item->quantity,
           $item->amount,
           $item->notes,
           $date->format('d M Y'),
           ucwords(strtolower($item->served_by->first_name.' '.$item->served_by->other_names))
       ];
    }
}
