<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Transaction extends Model
{
    //
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = "transactions";

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

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id', 'id');
    }

    public function getLotsAttribute()
    {
        $lots = [];
        if ($this->subject_type == 'LOT') {
            $lots[] = Lot::find($this->subject_id);
        } else if ($this->subject_type == 'LOT_GROUP') {
            $lotGroup = LotGroup::find($this->subject_id);
            $lots = $lotGroup->lots;
        }

        return $lots;
    }

    public function getCollectionsAttribute()
    {
        $collections = [];
        if ($this->subject_type == 'COLLECTION') {
            $collections[] = Collection::find($this->subject_id);
        } else if ($this->subject_type == 'COLLECTION_GROUP') {
            $collectionGroup = CollectionGroup::find($this->subject_id);
            $collections = $collectionGroup->collections;
        }

        return $collections;
    }

    public function getSubjectAttribute()
    {
        $subject = "";
        if ($this->subject_type == 'LOT') {
            $subject = Lot::find($this->subject_id)->lot_number;
        } else if ($this->subject_type == 'LOT_GROUP') {
            $subject = LotGroup::find($this->subject_id)->group_number;
        } else if ($this->subject_type == 'INVOICE') {
            $invoice = Invoice::find($this->subject_id);
            $subject = $invoice->invoice_number;
        }
        return $subject;
    }

    public function getPricingAttribute()
    {
        $qty = 0;
        $amount = $this->amount;
        

        if ($this->subject_type == 'LOT' || $this->subject_type == 'LOT_GROUP') {
            $lots = $this->lots;
            foreach ($lots as $lot) {
                $qty += $lot->quantity;
            }
        } else if ($this->subject_type == 'COLLECTION' || $this->subject_type == 'COLLECTION_GROUP') {
            $collections = $this->collections;
            foreach ($collections as $collection) {
                $qty += $collection->quantity;
            }
        }

        if ($qty == 0 || $amount == 0) {
            return 0;
        }

        return $amount / $qty;
    }

    public function getSenderAttribute()
    {
        if ($this->sender_type == 'COOPERATIVE') {
            $coop = Cooperative::find($this->sender_id);
            return $coop->name;
        } else if ($this->sender_type == 'MILLER') {
            $miller = Miller::find($this->sender_id);
            return $miller->name;
        } else if ($this->sender_type == 'CUSTOMER') {
            $customer = Customer::find($this->sender_id);
            return $customer->name;
        }
    }

    public function getRecipientAttribute()
    {
        return $this->recipient->name;
    }
    
    public function getFormattedAmountAttribute()
    {
    }
}
