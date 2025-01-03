<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class NewInvoiceItem extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "new_invoice_items";

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

    public function subject() {
        if ($this->item_type == 'Final Product'){
            return $this->belongsTo(FinalProduct::class, 'item_id', 'id');
        }

        return $this->belongsTo(MilledInventory::class, 'item_id', 'id');
    }

    public function getNumberAttribute(){
        if ($this->item_type == 'Final Product'){
            return $this->subject->product_number;
        }
        return $this->subject->inventory_number;
    }
}
