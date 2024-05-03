<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SaleItem extends Model
{
    use SoftDeletes;
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    protected $fillable = [
        'manufactured_product_id',
        'collection_id',
        'sales_id',
        'amount',
        'quantity',
        'discount',
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

    public function manufactured_product()
    {
        return $this->belongsTo(Production::class, 'manufactured_product_id','id');
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sales_id', 'id');
    }
}
