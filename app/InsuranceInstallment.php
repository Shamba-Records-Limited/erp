<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InsuranceInstallment extends Model
{
    const STATUS_PAID = 1;
    const STATUS_PARTIALLY_PAID = 2;
    const STATUS_PENDING = 3;

    const SOURCE_MPESA= 2;
    const SOURCE_WALLET=1;

    const TRX_TYPE = "Insurance Subscription";
    const REF_PREFIX = "INSPOLICY";

    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'subscription_id',
        'amount',
        'status',
        'due_date',
        'cooperative_id'
    ];


    public function getRouteKeyName()
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

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceSubscriber::class, 'subscription_id', 'id');
    }
}
