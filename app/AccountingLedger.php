<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingLedger extends Model
{
    protected $table = 'accounting_ledgers';

    public function accounting_rule(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AccountingRule::class);
    }

    public function parent_ledger(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ParentLedger::class, 'parent_ledger_id');
    }
}
