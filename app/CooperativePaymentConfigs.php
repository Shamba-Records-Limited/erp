<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CooperativePaymentConfigs extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        "shortcode",
        "name",
        "type",
        "consumer_key",
        "consumer_secret",
        "passkey",
        "initiator_name",
        "initiator_pass",
        "status",
        "cooperative_id"
    ];
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);

    }
}
