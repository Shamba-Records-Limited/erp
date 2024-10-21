<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesQuotationExport implements FromCollection, WithHeadings, WithMapping
{
    private $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return $this->sales;
    }

    public function map($item): array
    {
        $amount_details = $this->calculate_amount($item);
        return [
            $item->invoices->invoice_number . '-' . $item->invoices->invoice_count,
            $item->farmer_id ? '(Farmer) ' . (ucwords(strtolower($item->farmer->user->first_name . ' ' . $item->farmer->user->other_names))) : $item->customer->name,
            $item->saleItems->count(),
            $amount_details["amount"],
            $amount_details["discount"],
            $item->date,
        ];
    }

    public function headings(): array
    {
        return [
            'Batch No.',
            'Customer',
            'No. Of products',
            'Amount',
            'Discount',
            'Due Date',
        ];
    }

    private function calculate_amount($item): array
    {
        $tot_amt = 0;
        $tot_disc = $item->discount + $item->saleItems->sum('discount');
        foreach ($item->saleItems as $saleItem) {
            $tot_amt += $saleItem->amount * $saleItem->quantity;
        }
        $amount = $tot_amt - $tot_disc;

        return [
            "amount" => $amount,
            "discount" => $tot_disc
        ];
    }
}
