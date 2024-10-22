<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class PayrollStatus extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";

    const STATUS_PENDING = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

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

    public function payroll(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payroll::class,'payroll_id', 'id');
    }
}
