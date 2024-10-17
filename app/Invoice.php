<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Invoice extends Model
{
    use SoftDeletes;
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    const STATUS_PAID = 1;
    const STATUS_PARTIAL_PAID = 2;
    const STATUS_UNPAID = 0;
    const STATUS_RETURNS_RECORDED = 3;

    const DELIVERY_STATUS_DELIVERED = 1;
    const DELIVERY_STATUS_PENDING = 2;

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

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }


    public function invoice_payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }
}
