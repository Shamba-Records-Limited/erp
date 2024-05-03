<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditorsLedgersToAccountingLedgersTable extends Migration
{
    public function up()
    {
        DB::table('accounting_ledgers')->insert(
            ['name' => 'Creditors', 'type' => 'current', 'parent_ledger_id' => 2, 'ledger_code' => 20008]
        );
    }


    public function down()
    {
        DB::table('accounting_ledgers')
            ->where('name', '=', 'Creditors')
            ->where('ledger_code', '=', 20008)
            ->delete();
    }
}
