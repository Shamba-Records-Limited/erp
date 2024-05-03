<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class IncomeAndExpense extends Model
{
    protected  $keyType = "string";
    public $incrementing = false;
    protected $fillable = [
        'date',
        'income',
        'expense',
        'particulars',
        'cooperative_id',
        'user_id'
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

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
