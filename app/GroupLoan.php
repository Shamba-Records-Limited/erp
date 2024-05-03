<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupLoan extends Model
{
    use SoftDeletes;

    const STATUS_DISBURSED = 1;
    const STATUS_PAID = 2;
    const STATUS_PARTIALLY_PAID = 3;

    public function farmer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id', 'id');
    }

    public function group_loan_summery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GroupLoanSummary::class, 'group_loan_summary_id', 'id');
    }

}
