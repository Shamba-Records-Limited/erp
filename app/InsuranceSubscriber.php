<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceSubscriber extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_REDEEMED = 2;
    const STATUS_CANCELLED = 3;
    const STATUS_DEFAULTED_GRACE_PERIOD = 4;
    const STATUS_REDEEMED_PENALTY = 5;

    const MODE_MONTHLY = 1;
    const MODE_QUARTERLY = 2;
    const MODE_ANNUALLY = 3;
    const MODE_WEEKLY = 4;

    protected $fillable = [
        'farmer_id',
        'insurance_valuation_id',
        'insurance_product_id',
        'expiry_date',
        'status',
        'interest',
        'payment_mode',
        'cooperative_id',
        'period',
        'amount_claimed',
        'penalty',
        'grace_period'
    ];


    public function insurance_valuation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceValuation::class, 'insurance_valuation_id', 'id');
    }

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function insurance_product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceProduct::class, 'insurance_product_id', 'id');
    }

    public function dependants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InsuranceDependant::class, 'subscription_id', 'id');
    }
}
