<?php

namespace App\Exports;

use App\InsuranceTransactionHistory;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionHistoryExport implements FromCollection, WithMapping, WithHeadings
{
    private $cooperative;
    /**
     * @param $cooperative
     */

    public function __construct($cooperative)
    {
        $this->cooperative = $cooperative;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $transactions = InsuranceTransactionHistory::where('cooperative_id', $this->cooperative)->get();
        return collect($transactions);
    }

    public function map($row): array
    {
        return [
            ucwords(strtolower($row->subscription->farmer->user->first_name . ' ' . $row->subscription->farmer->user->other_names)),
            ucfirst(strtolower($row->subscription->insurance_product->name)),
            sprintf('%03d', $row->subscription->id),
            $row->type == InsuranceTransactionHistory::TYPE_REJECT_CLAIM ? 'Claim Rejected' : ($row->type == InsuranceTransactionHistory::TYPE_INSTALLMENT ? 'Paid Installment' : 'New Claim'),
            number_format($row->amount),
            $row->comments,
            Carbon::parse($row->date)->format('d M, Y'),
            $row->createdBy->first_name . ' ' . $row->createdBy->other_names,
        ];
    }

    public function headings(): array
    {
        return [
            'Farmer',
            'Product',
            'Policy No.',
            'Type',
            'Amount',
            'Comments',
            'Date',
            'Created By',
        ];
    }
}
