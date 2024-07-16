<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Receipt extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "receipts";

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

    public function items(){
        return $this->hasMany(ReceiptItem::class, 'receipt_id', 'id');
    }

    public function getItemsCountAttribute(){
       return ReceiptItem::where("receipt_id", $this->id)->count();
    }

    public function getTotalPriceAttribute(){
        $items = $this->items;
        $totalPrice = 0;
        foreach($items as $item){
            $totalPrice += ($item->price * $item->quantity);
        }
       return $totalPrice;
    }
}
