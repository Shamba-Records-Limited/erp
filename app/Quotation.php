<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Quotation extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "quotations";

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

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function items(){
        return $this->hasMany(QuotationItem::class, 'quotation_id', 'id');
    }

    public function getItemsCountAttribute(){
       return QuotationItem::where("quotation_id", $this->id)->count();
    }

    public function getTotalPriceAttribute(){
        $items = $this->items;
        $totalPrice = 0;
        foreach($items as $item){
            $totalPrice += ($item->price * $item->quantity);
        }
       return $totalPrice;
    }

    public function getHasInvoiceAttribute(){
        return NewInvoice::where("quotation_id", $this->id)->exists();
    }

    public function getNoInvoiceAttribute(){
        return !NewInvoice::where("quotation_id", $this->id)->exists();
    }
}
