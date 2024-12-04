<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Miller extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "millers";
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

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branches associated with the miller.
     */
    public function branches()
    {
        return $this->hasMany(MillerBranch::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
