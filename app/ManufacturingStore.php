<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ManufacturingStore extends Model
{
    protected $primarykey = "id";

    protected $keyType = "string";
    public $incrementing = false;

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

    protected $fillable = [
        'cooperative_id', 'name', 'location'
    ];
}
