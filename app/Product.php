<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Product extends Model
{
    protected $fillable = [
        'name','cooperative_id','mode', 'sale_price',
        'paye','serial_number','image','category_id', 'unit_id'
    ];


    protected  $primaryKey = "id";

    protected  $keyType = "string";

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
        return $this->belongsTo(Cooperative::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);

    }

    public function farmers()
    {
        return $this->belongsToMany(User::class,"farmers_products","product_id","farmer_id");
    }

    public function collections()
    {
        return $this->hasMany(Collection::class,"product_id");
    }

    public function crop(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Crop::class, 'product_id', 'id');
    }

    public static function  farmer_products($userId, $farmerId): array
    {
        return DB::select("
            SELECT
                p.id AS product_id,
                p.name AS product_name,
                p.buying_price as unit_cost,
                COALESCE(SUM(c.quantity * p.buying_price), 0) AS total_cost,
                COALESCE(SUM(c.quantity), 0) AS total_quantity,
                cat.name AS category,
                u.name AS unit
            FROM
                farmers_products fp
                    JOIN
                products p ON fp.product_id = p.id
                    JOIN
                categories cat ON p.category_id = cat.id
                    JOIN
                units u ON u.id = p.unit_id
                    LEFT JOIN
                collections c ON p.id = c.product_id AND c.farmer_id = '$farmerId'
            WHERE
                    fp.farmer_id = '$userId'
            GROUP BY
                p.id
            ORDER BY total_quantity DESC, unit_cost DESC;
        ");
    }


    public static function get_products($coop, $request, $limit){
        $products =  Product::where('cooperative_id', $coop);

        if($request){

            if($request->filter_unit){
                $products = $products->where('unit_id', $request->filter_unit);
            }

            if($request->filter_mode){
                $products = $products->where('mode', $request->filter_mode);
            }

            if($request->filter_serial_no){
                $products = $products->where('serial_number', $request->filter_serial_no);
            }

            if($request->filter_category){
                $products = $products->where('category_id', $request->filter_category);
            }
        }

        if($limit){
            return $products->latest()->limit($limit)->get();
        }else{
            return $products->latest()->get();
        }
    }
}
