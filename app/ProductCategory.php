<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ProductCategory extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "product_categories";

    protected $primaryKey = 'id';

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

    public function getUnitAttribute()
    {
        return $this->attributes['unit'];
    }
}
