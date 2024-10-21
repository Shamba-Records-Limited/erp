<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class DiseaseCategory extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name','cooperative_id'
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

    public static function categories($cooperativeId){
        return DiseaseCategory::where('cooperative_id',$cooperativeId)->latest()->get();
    }
}
