<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class RawMaterialSupplyHistory extends Model
{

    const SUPPLY_TYPE_COLLECTION = 1;
    const SUPPLY_TYPE_SUPPLIER = 2;

    const PAYMENT_STATUS_PAID = 1;
    const PAYMENT_STATUS_PENDING = 2;
    const PAYMENT_STATUS_PARTIAL = 3;

    const DELIVERY_STATUS_DELIVERED = 1;
    const DELIVERY_STATUS_PENDING = 2;

    public $incrementing = false;
    protected $fillable = [
        'supply_type',
        'raw_material_id',
        'supply_date',
        'amount',
        'quantity',
        'details'
    ];

    protected $primaryKey = "id";

    protected $keyType = "string";

    public function getRouteKeyName(): string
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

    public function raw_material(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id', 'id');
    }


    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }

    public function product_collection(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function manufacturing_store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ManufacturingStore::class, 'store_id', 'id');
    }

    public static function supplies($cooperative_id)
    {
        return DB::select(
            "
            select sum( if(rsh.delivery_status = 1, rsh.quantity, 0 )) as quantity,
                   count(rsh.raw_material_id) as total_count, rm.name, u.name as units, rm.id as id
            from raw_material_supply_histories rsh
            join raw_materials rm on rsh.raw_material_id = rm.id
            join units u on rm.unit_id = u.id
            where rsh.cooperative_id = '$cooperative_id'
            group by rsh.raw_material_id order by quantity desc;
            "
        );
    }

    public static function supply_histories($request, $coop, $raw_material_id, $limit)
    {
        $supplies = RawMaterialSupplyHistory::where('raw_material_id', $raw_material_id)
            ->where('cooperative_id', $coop);
        if ($request) {
            if ($request->purchase_order_number) {
                $supplies = $supplies->where('purchase_number', $request->purchase_order_number);
            }

            if ($request->product) {
                $supplies = $supplies->where('product_id', $request->product);
            }
            if ($request->date) {
                $dates = split_dates($request->date);
                $from = $dates['from'];
                $to = $dates['to'];
                $supplies = $supplies->whereBetween('supply_date', [$from, $to]);
            }

            if ($request->supplier_type) {
                $supplies = $supplies->where('supply_type', $request->supplier_type);
            }

            if ($request->supplier) {
                $supplies = $supplies->where('supplier_id', $request->supplier);
            }

            if ($request->store) {
                $supplies = $supplies->where('store_id', $request->store);
            }
            if ($request->payment_status) {
                $supplies = $supplies->where('payment_status', $request->payment_status);
            }
            if ($request->delivery_status) {
                $supplies = $supplies->where('delivery_status', $request->delivery_status);
            }
        }


        if ($limit) {
            return $supplies->latest()->limit($limit)->get();
        } else {
            return $supplies->latest()->get();
        }
    }

    public static function supply_history_by_store($request, $coop, $limit, $storeId){
        $supplies = RawMaterialSupplyHistory::where('cooperative_id', $coop)
            ->where('store_id', $storeId);

        if ($request) {

            if($request->raw_material){
                $supplies = $supplies->where('raw_material_id', $request->raw_material);
            }
            if ($request->purchase_order_number) {
                $supplies = $supplies->where('purchase_number', $request->purchase_order_number);
            }

            if ($request->product) {
                $supplies = $supplies->where('product_id', $request->product);
            }
            if ($request->date) {
                $dates = split_dates($request->date);
                $from = $dates['from'];
                $to = $dates['to'];
                $supplies = $supplies->whereBetween('supply_date', [$from, $to]);
            }

            if ($request->supplier_type) {
                $supplies = $supplies->where('supply_type', $request->supplier_type);
            }

            if ($request->supplier) {
                $supplies = $supplies->where('supplier_id', $request->supplier);
            }

            if ($request->payment_status) {
                $supplies = $supplies->where('payment_status', $request->payment_status);
            }
            if ($request->delivery_status) {
                $supplies = $supplies->where('delivery_status', $request->delivery_status);
            }
        }

        if ($limit) {
            return $supplies->latest()->limit($limit)->get();
        } else {
            return $supplies->latest()->get();
        }
    }
}
