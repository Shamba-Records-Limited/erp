<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CooperativeWalletTransaction extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = "cooperative_wallet_transactions";


    protected $primaryKey = 'id';

    protected $fillable = [
        'cooperative_wallet_id',
        'amount',
        'type',
        'description',
        'reference',
        'source',
        'date',
        'proof_of_payment'
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

    public function cooperative_wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CooperativeWallet::class);
    }

}
