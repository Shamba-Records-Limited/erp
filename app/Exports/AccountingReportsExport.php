<?php

namespace App\Exports;

use App\CooperativeFinancialPeriod;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountingReportsExport implements FromCollection, WithHeadings, WithMapping
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
        $fy = CooperativeFinancialPeriod::where('cooperative_id', $this->cooperativeId)
        ->orderBy('end_period', 'desc')
        ->get();
        return collect($fy);
    }

    public function headings(): array
    {
        return [
            'Start Date',
            'End Date',
            'Type',
            'Balance CF',
            'Balance BF',
            'Status'
        ];
    }

    public function map($f): array
    {
        return [
            $f->start_period,
            $f->end_period,
            strtolower($f->type) === 'monthly' ? strtoupper($f->type) : 
            (strtolower($f->type) === 'quarterly' ? strtoupper($f->type) : 
            strtoupper($f->type)),
            $f->balance_cf !== null ? number_format($f->balance_cf, 2, '.', ','): '-',
            number_format($f->balance_bf, 2, '.', ','),
            $f->active ? 'Active' : (Carbon::parse($f->end_period)->gt(Carbon::now()) ? 'Inactive' : 'Closed')
        ];

    }
}
