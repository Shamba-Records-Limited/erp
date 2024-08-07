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

    public function getLotsAttribute(){
        $lots = [];
        if($this->subject_type == 'LOT'){
            $lots[] = Lot::find($this->subject_id);
        } else if($this->subject_type == 'LOT_GROUP'){
            $lotGroup = LotGroup::find($this->subject_id);
            $lots = $lotGroup->lots;
        }

        return $lots;
    }

    public function getSubjectAttribute(){
        $subject = "";
        if($this->subject_type == 'LOT'){
            $subject = Lot::find($this->subject_id)->lot_number;
        } else if($this->subject_type == 'LOT_GROUP'){
            $subject = LotGroup::find($this->subject_id)->group_number;
        }
        return $subject;
    }
}
