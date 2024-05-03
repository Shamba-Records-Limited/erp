<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Customer extends Model
{

    const CUSTOMER_TYPE_INDIVIDUAL = 1;
    const CUSTOMER_TYPE_COMPANY = 2;
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    protected $table = "customers";

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


    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

}
