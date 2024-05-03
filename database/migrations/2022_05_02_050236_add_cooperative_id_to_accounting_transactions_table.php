<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCooperativeIdToAccountingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounting_transactions', function (Blueprint $table) {
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->on('cooperatives')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('accounting_transactions', 'cooperative_id'))
        {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->dropForeign('accounting_transactions_cooperative_id_foreign');
                $table->dropColumn('cooperative_id');
            });
        }
    }
}
