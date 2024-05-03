<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InsuranceTransactionHistory extends Model
{
    const TYPE_CLAIM = 1;
    const TYPE_INSTALLMENT = 2;
    const TYPE_REJECT_CLAIM = 3;

    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $fillable = ['subscription_id', 'amount', 'type', 'date', 'created_by', 'comments', 'cooperative_id'];

    public function getRouteKeyName()
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

    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceSubscriber::class, 'subscription_id', 'id');
    }

    public function createdBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
