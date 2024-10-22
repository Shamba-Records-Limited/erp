<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Production extends Model
{
    use SoftDeletes;
    protected  $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        'final_product_id',
        'quantity',
        'available_quantity',
        'unit_id',
        'profits_expected',
        'final_selling_price',

        'expiry_date'
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
        self::deleting(function ($model) {
            if($model->rawMaterial){
                $model->rawMaterial->each->delete();
            }
        });
    }
    public function finalProduct()
    {
        return $this->belongsTo(FinalProduct::class, 'final_product_id','id');
    }
    public function rawMaterials(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductionMaterial::class, 'production_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id','id');
    }

    public function manufacturing_store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ManufacturingStore::class,'manufacturing_store_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }


    public static function get_production_summery($coop, $limit): array
    {
        $base_query = "
        select SUM( pm.cost * pm.quantity)/p.available_quantity AS production_cost, fp.name as product, ms.name as store, p.available_quantity, u.name as units, p.final_selling_price, p.id
        from production_materials pm
        join production_histories ph on pm.production_history_id = ph.id
        join productions p on ph.production_id = p.id
        join final_products fp on p.final_product_id = fp.id
        join manufacturing_stores ms on p.manufacturing_store_id = ms.id
        join units u on fp.unit_id = u.id
        where p.cooperative_id = '$coop'                                                                                                                                               
        group by p.id, fp.name, p.final_selling_price, ms.name order by production_cost desc";

        if($limit > 0){
            $base_query .= ' limit '.$limit;
        }

        return \DB::select($base_query);
    }

    public static  function get_stock_value($cooperative){
        $base_query = "
        select  SUM(p.available_quantity * p.final_selling_price) as stock_value
        from  productions p
        where p.cooperative_id = '$cooperative'";
        return \DB::select($base_query)[0]->stock_value;
    }
}
