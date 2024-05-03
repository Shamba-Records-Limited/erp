<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFinancialPeriodIdFromAccountingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('accounting_transactions', 'cooperative_financial_period_id'))
        {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->dropForeign('accounting_transactions_cooperative_financial_period_id_foreign');
                $table->dropColumn('cooperative_financial_period_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!Schema::hasColumn('accounting_transactions', 'cooperative_financial_period_id')) {
            Schema::table('accounting_transactions', function (Blueprint $table) {
                $table->uuid('cooperative_financial_period_id');
                $table->foreign('cooperative_financial_period_id')->on('cooperative_financial_periods')
                    ->references('id')
                    ->onDelete('restrict')
                    ->onUpdate('cascade');
            });
        }
    }
}
