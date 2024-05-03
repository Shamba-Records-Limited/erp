<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingSupplyDetailsExport implements FromCollection, WithMapping, WithHeadings
{
     private $supplies;

    public function __construct($supplies){
        $this->supplies = $supplies;
    }
    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->supplies);
    }

    public function headings(): array
    {
        return [
            'Order No.',
            'Raw Material',
            'Supply Type',
            'Supplier',
            'Product',
            'Supply Date',
            'Unit Cost',
            'Balance',
            'Quantity',
            'Stock Value',
            'Payment Status',
            'Delivery Status',
            'Store',
            'Notes',
            'Recorded By',
        ];
    }

    public function map($supply): array
    {
        return [
            $supply->purchase_number,
            $supply->raw_material->name,
            config('enums')["supply_types"][0][$supply->supply_type],
            $supply->supply_type  == \App\RawMaterialSupplyHistory::SUPPLY_TYPE_SUPPLIER ? ucwords(strtolower($supply->supplier->name)) : '',
            $supply->supply_type  == \App\RawMaterialSupplyHistory::SUPPLY_TYPE_COLLECTION ? ucwords(strtolower($supply->product_collection->name)) : '',
            \Carbon\Carbon::parse($supply->supply_date)->format('D, d M Y'),
            $supply->amount/$supply->quantity,
            $supply->balance,
            $supply->quantity,
            $supply->amount,
            config('enums')["supply_payment_status"][0][$supply->payment_status],
            config('enums')["delivery_status"][0][$supply->delivery_status],
            $supply->manufacturing_store->name,
            $supply->details,
            ucwords(strtolower($supply->user->first_name.' '.$supply->user->other_names))
        ];
    }
}
