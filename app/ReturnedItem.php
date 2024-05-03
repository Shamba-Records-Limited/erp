<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class ReturnedItem extends Model
{
    protected  $primaryKey = "id";

    protected  $keyType = "string";

    protected $table = "returned_items";

    public $incrementing = false;

    protected $fillable = [
        'sale_id',
        'collection_id',
        'manufactured_product_id',
        'quantity',
        'amount',
        'date',
        'served_by_id',
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

    public function manufactured_product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Production::class, 'manufactured_product_id', 'id');
    }

    public function collection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id', 'id');
    }

    public function sale(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function served_by(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'served_by_id', 'id');
    }

    public function cooperative(){
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }
}
