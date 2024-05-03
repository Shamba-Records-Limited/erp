<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    private $products;

    public function __construct($products)
    {
        $this->products = $products;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->products);
    }

    public function headings(): array
    {
        return [
            'Product',
            'Category',
            'Unit',
            'Mode',
            'Buying Price',
            'Selling Price',
            'VAT %',
            'Selling+VAT',
            'Profit Margin',
            'Serial Number',
            'Minimum Quantity Alert'
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->category->name,
            $product->unit->name,
            $product->mode,
            $product->buying_price,
            $product->sale_price,
            $product->vat,
            (($product->sale_price * $product->vat)/100)+$product->sale_price,
            $product->sale_price - $product->buying_price,
            $product->serial_number,
            $product->threshold.' '.$product->unit->name
        ];
    }
}
