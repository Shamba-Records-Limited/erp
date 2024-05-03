<?php

namespace App\Exports;

use App\Production;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingReportExport implements FromCollection,WithMapping,WithHeadings
{
    private $cooperative_id;

    public function __construct($cooperative_id)
    {
        $this->cooperative_id = $cooperative_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        $cooperativeId = $this->cooperative_id;
        $latest_productions = Production::whereHas('finalProduct', function($query) use($cooperativeId) {
            $query->where('cooperative_id', $cooperativeId);
        })->with(['finalProduct'])->latest()->get();
        return collect($latest_productions);
    }

    public function map($productions): array
    {
        $cost = 0;
        if($productions->rawMaterial) {
            foreach($productions->rawMaterial as $material) {
                $material;
            }
        }
        return [
            $productions->finalProduct->name,
            $productions->quantity.' '.$productions->unit->name,
            $material->rawMaterial->product_id ? $material->rawMaterial->product->name : $material->rawMaterial->name,
            $productions->finalproduct->cooperative->currency.' '.($material->rawMaterial->product_id ? $cost+=$material->rawMaterial->product->sale_price : $cost+=$material->rawMaterial->estimated_cost),
            $productions->finalproduct->cooperative->currency.' '.number_format($productions->final_selling_price,2,'.',','),
            $productions->final_selling_price-$cost,
        ];
    }
    public function headings(): array
    {
        return [
            'Product',
            'Quantity',
            'Raw Material',
            'Cost',
            'Selling Price',
            'Profits'
        ];
    }
}
