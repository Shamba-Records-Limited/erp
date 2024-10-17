<?php

namespace App\Exports;

use App\InsuranceSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InsuranceSubscriptionsExport implements FromCollection, WithMapping, WithHeadings
{
    private $cooperative;

    public function __construct($cooperative)
    {
        $this->cooperative = $cooperative;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $subscriptions = InsuranceSubscriber::where('cooperative_id', $this->cooperative)->get();
        return collect($subscriptions);
    }

    public function map($subscription): array
    {
        return [
            sprintf('%03d', $subscription->id),
            ucwords(strtolower($subscription->farmer->user->first_name . ' ' . $subscription->farmer->user->other_names)),
            ucfirst(strtolower($subscription->insurance_product->name)),
            $subscription->payment_mode == InsuranceSubscriber::MODE_MONTHLY ? 'Monthly' : ($subscription->payment_mode == InsuranceSubscriber::MODE_QUARTERLY ? 'Quarterly' : ($subscription->payment_mode == InsuranceSubscriber::MODE_WEEKLY ? 'Weekly' : 'Annually')),
            $subscription->period . ' Years',
            $subscription->expiry_date,
            $subscription->insurance_valuation_id ? 'Yes' : 'No',
            $subscription->grace_period . ' days',
            $subscription->penalty . '%',
            $subscription->status == InsuranceSubscriber::STATUS_CANCELLED ? 'Cancelled' : ($subscription->status == InsuranceSubscriber::STATUS_ACTIVE ? 'Active' : ($subscription->status == InsuranceSubscriber::STATUS_REDEEMED ? 'Redeemed' : ($subscription->status == InsuranceSubscriber::STATUS_DEFAULTED_GRACE_PERIOD ? 'Defaulted: In grace period' : 'Redeemed with penalty'))),
        ];
        
    }

    public function headings(): array
    {
        return [
            'Policy No.',
            'Farmer',
            'Product',
            'Payment mode',
            'Period',
            'Expiry Date',
            'Valuated',
            'Grace Period',
            'Penalty',
            'Status'
        ];
    }
}
