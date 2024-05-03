<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPaymentHistory extends Model
{
    protected $keyType = 'string';

    public $incrementing = false;

    protected $primaryKey = 'id';

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function loan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function wallet_transaction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }
}
