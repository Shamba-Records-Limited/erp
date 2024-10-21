<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class GroupLoanRepayment extends Model
{
    protected $keyType = "string";
    public $incrementing = false;
    const STATUS_INITIATED = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_FAILED = 3;

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::generate(4);
        });
    }

    public function initiated_by(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by_id', 'id');
    }

    public function group_loan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GroupLoan::class, 'group_loan_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }
}
