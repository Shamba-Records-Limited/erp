<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InsuranceProduct extends Model
{
    const TYPE_SERVICE = 1;
    const TYPE_SAVING = 2;
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    protected $table = "insurance_products";
    protected $fillable = ['name', 'premium', 'period', 'interest','cooperative_id', 'type'];

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

    public function benefits(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(InsuranceBenefit::class,"insurance_products_benefits","insurance_product_id","benefit_id");
    }

    public static function products($cooperativeId){
        return InsuranceProduct::where('cooperative_id', $cooperativeId)->get();
    }

}
