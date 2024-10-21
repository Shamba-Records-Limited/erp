<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class LoanSetting extends Model
{
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    const REPAYMENT_MODE_ONE_OFF = 1;
    const REPAYMENT_MODE_INSTALLMENTS = 2;

    protected $fillable = [
        'type',
        'interest',
        'penalty',
        'period',
        'installments',
        'cooperative_id'
    ];
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
}
