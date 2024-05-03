<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassificationToAccountingLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->enum('classification', [ 'ACCOUNT_PAYABLES', 'ACCOUNT_RECEIVABLES' ])->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->dropColumn([ 'classification' ]);
        });
    }
}
