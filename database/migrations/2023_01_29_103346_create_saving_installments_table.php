<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_installments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('saving_id');
            $table->uuid('wallet_transaction_id');
            $table->foreign('saving_id')->references('id')->on('saving_accounts')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('wallet_transaction_id')->references('id')->on('wallet_transactions')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saving_installments');
    }
}
