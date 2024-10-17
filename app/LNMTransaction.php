<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LNMTransaction extends Model
{
    const STATUS_INITIATED = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }
}
