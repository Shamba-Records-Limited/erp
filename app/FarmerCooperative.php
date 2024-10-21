<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class FarmerCooperative extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "farmer_cooperative";


    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
            $model->added_by_id = Auth::id();
        });
    }

}
