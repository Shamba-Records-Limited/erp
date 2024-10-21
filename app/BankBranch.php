<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class BankBranch extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';

    protected $fillable = [
        'name','code','address','cooperative_id','bank_id'
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

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public static function getByBankId($bank_id)
    {
        return BankBranch::where('bank_id', $bank_id)->orderBy('name', 'asc')->get();
    }
}
