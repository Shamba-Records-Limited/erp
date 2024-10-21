<?php

namespace App\Exports;

use App\FinalProduct;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ManufacturingProductsExport implements FromCollection,WithMapping,WithHeadings
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
        $final_products = FinalProduct::where('cooperative_id',$this->cooperative_id)->latest()->get();
        return collect($final_products);
    }

    public function map($prod): array
    {
        return [
            $prod->name,
            $prod->category->name,
            number_format($prod->selling_price,2,'.',','),
            $prod->unit->name,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Category',
            'Selling Price/Unit',
            'Unit'
        ];
    }
}
