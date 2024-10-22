<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Country extends Model
{

    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = [
        'name','iso_code'
    ];


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

    public function cooperatives()
    {
        return $this->hasMany(Cooperative::class);
    }
}
