<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class AccountingRule extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'accounting_rules';
    protected $primaryKey = 'id';


    public function getRouteKeyName()
    {
        return 'id';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) Uuid::generate(4);
        });
    }

    public function cooperative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cooperative::class);
    }

    public function debit_ledger(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AccountingLedger::class, 'debit_ledger_id');
    }

    public function credit_ledger(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AccountingLedger::class, 'credit_ledger_id');
    }
}
