<?php

namespace App\Exports;

use App\ProductionHistory;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpiredStockExport implements FromCollection, WithMapping, WithHeadings
{
    private $productions;

    public function __construct($productions)
    {
        $this->productions = $productions;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->productions);
    }

    public function headings(): array
    {
        return [
            'Production Lot',
            'Product',
            'Quantity',
            'Unit Price',
            'Total Value',
            'Expires',
            'Expiry Date',
            'Expiry Status',
            'Created By',
        ];
    }

    public function map($production): array
    {
        return [
            $production->production_lot,
            $production->production->finalProduct->name,
            number_format($production->quantity),
            number_format($production->unit_price),
            number_format($production->unit_price*$production->quantity),
            config('enums')["will_expire"][0][$production->expires],
            Carbon::parse($production->expiry_date)->format('D, d M Y'),
            $production->expiry_status == ProductionHistory::EXPIRY_STATUS_EXPIRED ? 'Expired' : 'Valid Status',
            ucwords(strtolower($production->registered_by->first_name . ' ' . $production->registered_by->other_names))
        ];
    }
}
