<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCooperativeIdToAccountingLedgers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_ledgers', function (Blueprint $table) {
            $table->uuid("cooperative_id")->after('type')->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
            $table->dropForeign(['accounting_ledgers_cooperative_id_foreign']);
            $table->dropColumn('cooperative_id');
        });
    }
}
