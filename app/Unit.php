<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Unit extends Model
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

    private function products()
    {
        return $this->hasMany(Product::class);
    }

    public static  function units($cooperative){
        return Unit::select(['name', 'created_at'])->latest()->where('cooperative_id', $cooperative)->get();
    }
}
