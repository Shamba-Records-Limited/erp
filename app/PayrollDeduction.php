<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class PayrollDeduction extends Model
{

    const BEFORE_PAYE_DEDUCTION = 1;
    const AFTER_PAYE_PAYE_DEDUCTION = 2;

    const DEDUCTION_ON_GROSS_PAY_YES= 1;
    const DEDUCTION_ON_GROSS_PAY_NO= 0;
    protected  $keyType = "string";
    public $incrementing = false;
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
