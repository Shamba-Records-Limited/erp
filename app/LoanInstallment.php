<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class LoanInstallment extends Model
{
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;
    const STATUS_PARTIALLY_PAID = 2;
    const STATUS_FAILED = 3;

    const TRX_TYPE = "Repay Loan";
    const REF_PREFIX = "REPAY";

    const WALLET_REPAYMENT_OPTION = 1;
    const MPESA_REPAYMENT_OPTION = 2;


    protected $fillable = [
        'amount',
        'date',
        'status',//0 - pending, 1 - paid, 2 - partial payment 
        'loan_id'
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

    public function loan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}
