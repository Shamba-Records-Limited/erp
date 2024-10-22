<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class VetService extends Model
{
    protected $fillable = [
        'name', 'description', 'cooperative_id'
    ];


    protected $primaryKey = "id";

    protected $table = "vet_services";

    protected $keyType = "string";

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

    public static function services($cooperativeId)
    {
        return VetService::select('id', 'name')
            ->where('cooperative_id', $cooperativeId)->get();
    }
}
