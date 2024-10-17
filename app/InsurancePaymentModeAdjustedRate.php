<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsurancePaymentModeAdjustedRate extends Model
{
    protected $table = 'insurance_payment_mode_adjusted_rates';
    protected $fillable = ['adjusted_rate','payment_mode', 'cooperative_id'];

    public static function adjustmentRates($cooperativeId){
        return InsurancePaymentModeAdjustedRate::where('cooperative_id', $cooperativeId)->get();
    }
}
