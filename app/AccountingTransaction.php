<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class AccountingTransaction extends Model
{
    protected $table = 'accounting_transactions';

    protected $keyType = 'string';

    public $incrementing = false;


    protected $primaryKey = 'id';


    public function getRouteKeyName(): string
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

    public function accounting_ledger(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AccountingLedger::class);
    }

    public static function accountingTransactions($cooperative_id, $limit=0): \Illuminate\Support\Collection
    {
        $query = AccountingTransaction::select(
            'accounting_transactions.debit as debit',
            'accounting_transactions.credit as credit',
            'accounting_transactions.ref_no as ref_no',
            'accounting_transactions.particulars as particulars',
            'accounting_transactions.date as date',
            'accounting_ledgers.name as ledger',
            'accounting_ledgers.type as ledger_type',
            'parent_ledgers.name as account_type'
        )
            ->join('accounting_ledgers', 'accounting_ledgers.id', '=', 'accounting_transactions.accounting_ledger_id')
            ->join('parent_ledgers', 'parent_ledgers.id', '=', 'accounting_ledgers.parent_ledger_id')
            ->where('accounting_transactions.cooperative_id', $cooperative_id)
            ->orderBy('accounting_transactions.created_at', 'desc');
            if($limit > 0){
                return $query->limit($limit)->get();
            }
           return $query->get();
    }
}
