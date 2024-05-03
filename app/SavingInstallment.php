<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SavingInstallment extends Model
{
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    public $timestamps = false;

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

    public function wallet_transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class, 'wallet_transaction_id', 'id');
    }

    public function saving_account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SavingAccount::class, 'saving_id', 'id');
    }
}
