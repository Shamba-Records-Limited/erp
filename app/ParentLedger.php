<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentLedger extends Model
{
    protected $table = 'parent_ledgers';

    public function accounting_ledgers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AccountingLedger::class);
    }
}
