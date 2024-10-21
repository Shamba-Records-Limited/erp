<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_financial_period_id');
            $table->foreign('cooperative_financial_period_id')->on('cooperative_financial_periods')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->unsignedBigInteger('accounting_ledger_id');
            $table->foreign('accounting_ledger_id')->on('accounting_ledgers')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->date('date');
            $table->double('credit')->nullable();
            $table->double('debit')->nullable();
            $table->text('particulars')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting_transactions');
    }
}
