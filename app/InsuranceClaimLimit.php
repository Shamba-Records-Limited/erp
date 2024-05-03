<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class InsuranceClaimLimit extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = ['product_id', 'limit_rate', 'amount', 'cooperative_id'];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string)Uuid::generate(4);
        });
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InsuranceProduct::class, 'product_id', 'id');
    }

    public static function productLimits($user){
        return InsuranceClaimLimit::where('cooperative_id', $user->cooperative_id)->get();
    }

}
