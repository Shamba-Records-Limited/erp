<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class NewInvoice extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "new_invoices";

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
        return $this->hasMany(NewInvoiceItem::class, 'new_invoice_id', 'id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function getItemsCountAttribute(){
       return NewInvoiceItem::where("new_invoice_id", $this->id)->count();
    }

    public function getTotalPriceAttribute(){
        $items = $this->items;
        $totalPrice = 0;
        foreach($items as $item){
            $totalPrice += ($item->price * $item->quantity);
        }
       return $totalPrice;
    }

    public function receipt(){
        return $this->hasOne(Receipt::class, 'new_invoice_id', 'id');
    }

    public function getHasReceiptAttribute(){
       return Receipt::where("new_invoice_id", $this->id)->exists();

    }
}
