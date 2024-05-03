<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class Sale extends Model
{
    use SoftDeletes;

    const SALE_TYPE_QUOTATION = 'quotation';
    const SALE_TYPE_SALE = 'sale';

    protected  $primaryKey = "id";

    protected  $keyType = "string";

    protected $table = "sales";

    public $incrementing = false;

    protected $fillable = [
        'farmer_id',
        'user_id',
        'cooperative_id',//seller
        'customer_id',
        'sale_batch_number',
        'date',
        'type',//[sale or quotation],
        'discount',
        'notes',
        'save_type',//draft,saved
        'recurring',
        'toc'
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

    public function farmer()
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }


    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }

    public function invoices()
    {
        return $this->hasOne(Invoice::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'sales_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public static function purchases_query($farmer_id){
        return DB::select("
        SELECT s.id, CONCAT(s.sale_batch_number, '-',s.sale_count ) as sale_batch_number,
               SUM(si.amount * si.quantity) as amount,
               s.discount, s.date, s.balance, s.paid_amount, i.status, ri.amount as returns_value
        FROM sales s
                JOIN sale_items si ON s.id = si.sales_id
                JOIN invoices i ON i.sale_id = s.id
                LEFT JOIN returned_items ri on s.id = ri.sale_id
                WHERE s.farmer_id = '$farmer_id' AND s.deleted_at IS NULL
                GROUP BY si.sales_id, s.sale_count, s.sale_count, i.id, ri.id
                ORDER BY s.sale_count DESC
        ");

    }
}
