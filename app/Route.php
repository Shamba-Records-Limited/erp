<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Route extends Model
{
    protected $fillable = [
        'name','cooperative_id'
    ];


    protected  $primaryKey = "id";

    protected  $keyType = "string";

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

}
