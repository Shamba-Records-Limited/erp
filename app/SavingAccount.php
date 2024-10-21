<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class SavingAccount extends Model
{

    const STATUS_ACTIVE = 1;
    const STATUS_WITHDRAWN = 2;


    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function saving_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SavingType::class);
    }
    public function saving_trail()
    {
        return $this->hasMany(SavingInstallment::class, 'saving_id');
    }
}
