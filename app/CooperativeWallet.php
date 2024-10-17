<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class CooperativeWallet extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = "cooperative_wallets";


    protected $primaryKey = 'id';

    protected $fillable = [
        'balance',
        'cooperative_id',
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
