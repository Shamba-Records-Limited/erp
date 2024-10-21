<?php

namespace App\Exports;

use App\VetItem;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VetItemsExport implements FromCollection,WithMapping,WithHeadings
{
    private $cooperativeId;

    public function __construct($cooperativeId)
    {
        $this->cooperativeId = $cooperativeId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $vet_items = VetItem::where('cooperative_id', $this->cooperativeId)->latest()->get();
        return collect($vet_items);
    }

    public function map($item): array
    {
        return [
            $item->name,
            $item->unit->name,
            number_format($item->bp, 2,'.',','),
            number_format($item->sp,2,'.',','),
            number_format($item->quantity,2,'.',',').' '.$item->unit->name,
            number_format($item->sold_quantity,2,'.',',').' '.$item->unit->name,
            number_format(($item->sp - $item->bp) ,2,'.',',').' per '.$item->unit->name,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Unit Measure',
            'Buying Price',
            'Selling Price',
            'Available Quantity',
            'Sold Quantity',
            'Profit'
        ];
    }
}
