<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupLoanSummary extends Model
{

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class, 'cooperative_id','id');
    }

    public function created_by(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
    public function group_loan_type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(GroupLoanType::class, 'group_loan_type_id','id');
    }
}
