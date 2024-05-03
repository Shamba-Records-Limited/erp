<?php

namespace App\Exports;

use App\Invoice;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
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
            $item->balance,
            $item->date,
            $this->status($item),
            $this->delivery_status($item),
            Carbon::parse($item->created_at)->format('d M Y'),
            ucwords(strtolower($item->user->first_name.' '.$item->user->other_names))
        ];
    }


    public function headings(): array
    {
        return [
            'Invoice No.',
            'Customer',
            'No. Of products',
            'Amount',
            'Discount',
            'Balance',
            'Due Date',
            'Status',
            'Delivery Status',
            'Date Created',
            'Served By'
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

    private function status($item): string
    {
        if ($item->invoices->status === Invoice::STATUS_UNPAID) {
            return "Unpaid";
        } elseif ($item->invoices->status === Invoice::STATUS_PARTIAL_PAID) {
            return "Partially Paid";
        } elseif ($item->invoices->status === Invoice::STATUS_PAID) {
            return "Paid";
        } elseif ($item->invoices->status === Invoice::STATUS_RETURNS_RECORDED) {
            return "Returns Recorded";
        } else {
            return "Pending";
        }
    }

    private function delivery_status($item): string
    {
        if ($item->invoices->delivery_status === Invoice::DELIVERY_STATUS_DELIVERED) {
            return "Delivered";
        } else {
            return "Pending";
        }
    }

}
