<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuppliersLedgerToAccountingLedgersTable extends Migration
{
    public function up()
    {
        DB::table('accounting_ledgers')->insert(
            ['name' => 'Suppliers', 'type' => 'current', 'parent_ledger_id' => 2, 'ledger_code' => 20009]
        );
    }


    public function down()
    {
        DB::table('accounting_ledgers')
            ->where('name', '=', 'Suppliers')
            ->where('ledger_code', '=', 20009)
            ->delete();
    }
}
