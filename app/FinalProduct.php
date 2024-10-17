<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinalProduct extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        'name',
        'category_id',
        'cooperative_id',
        'unit_id',
        'selling_price',
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
    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id','id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
    }

    public function production() {
        return $this->hasMany(Production::class, 'final_product_id','id');
    }
}
