<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Supplier extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'supplier_type',
        'name',
        'email',
        'title',
        'gender',
        'phone_number',
        'location',
        'address',
        'cooperative_id'
    ];

    protected $primaryKey = "id";

    protected $keyType = "string";

    public function getRouteKeyName(): string
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

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);

    }
}
