<?php

namespace App\Exports;

use App\InsuranceClaim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InsuranceProductClaimsMngtExport implements FromCollection, WithHeadings, WithMapping
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
        $claims = InsuranceClaim::where('cooperative_id', $this->cooperativeId)
        ->orderBy('created_at', 'desc')->get();
        return collect($claims);
    }

    public function headings(): array
    {
        return [
            'Farmer',
            'Product',
            'Dependant',
            'Amount',
            'Remaining Limit',
            'Status',
            'Description'
        ];
    }

    public function map($c): array
    {
        $total_amount = 0;
        $total_amount += $c->amount;
        return [
            ucwords(strtolower($c->subscription->farmer->user->first_name.' '.$c->subscription->farmer->user->other_names)),
            $c->subscription->insurance_product->name,
            $c->dependant_id ? $c->dependant->name : '-',
            number_format($c->amount),
            number_format($c->subscription->current_limit),
            $c->status == InsuranceClaim::STATUS_PENDING ? 'Pending' :
            ($c->status == InsuranceClaim::STATUS_APPROVED ? 'Approved' :
            ($c->status == InsuranceClaim::STATUS_REJECTED ? 'Rejected' :
            ($c->status == InsuranceClaim::STATUS_SETTLED ? 'Settled' : ''))),
            $c->description
        ];
    }
}
