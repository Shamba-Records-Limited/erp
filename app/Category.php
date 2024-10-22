<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Category extends Model
{
    protected $fillable = [
        'name'
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

    public function products(){
        return $this->hasMany(Category::class);
    }
}
