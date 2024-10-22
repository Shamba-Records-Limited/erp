<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanApplicationDetail extends Model
{
    public function loan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id', 'id');
    }
}
