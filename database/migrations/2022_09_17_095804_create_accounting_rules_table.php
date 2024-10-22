<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->unsignedBigInteger('debit_ledger_id');
            $table->foreign('debit_ledger_id')->references('id')->on('accounting_ledgers')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->unsignedBigInteger('credit_ledger_id');
            $table->foreign('credit_ledger_id')->references('id')->on('accounting_ledgers')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->text('description')->nullable();
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting_rules');
    }
}
