<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InvoicePayment extends Model
{
    public $incrementing = false;
    protected  $primaryKey = "id";

    protected  $keyType = "string";
    const PAYMENT_MODE_MPESA_STK_PUSH = 'mpesa-2';
    const PAYMENT_MODE_MPESA_OFFLINE = 'mpesa-1';
    const PAYMENT_MODE_BANK = 'bank';
    const PAYMENT_MODE_CASH = 'cash';
    const PAYMENT_MODE_WALLET = 'wallet';

    const PAYMENT_STATUS_SUCCESS = 1;
    const PAYMENT_STATUS_IN_PROGRESS = 2;
    const PAYMENT_STATUS_FAILED = 3;

    const paymentModsDisplay = [
        self::PAYMENT_MODE_MPESA_STK_PUSH => "MPESA STK Push",
        self::PAYMENT_MODE_MPESA_OFFLINE => "MPESA Offline",
        self::PAYMENT_MODE_CASH => "Cash",
        self::PAYMENT_MODE_WALLET => "Wallet",
        self::PAYMENT_MODE_BANK => "Bank"
    ];

    protected $fillable = [
        'invoice_id',
        'payment_platform',
        'transaction_number',
        'instructions',
        'amount'
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


    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function initiated_by(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator', 'id');
    }
}
