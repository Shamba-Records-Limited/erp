<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('wallet_id');
            $table->string('type'); //payment, withdrawal, loan, load, collection
            $table->double('amount')->default(0);
            $table->string('reference');
            $table->string('source'); //mpesa, bank, internal
            $table->uuid('initiator_id');
            $table->string('description'); //
            $table->string('phone')->nullable(true);
            $table->foreign('wallet_id')->references('id')->on('wallets')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('initiator_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('wallet_transactions');
    }
}
