<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperativeWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperative_wallet_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_wallet_id');
            $table->foreign('cooperative_wallet_id')->on('cooperative_wallets')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->double('amount')->default(0);
            $table->enum('type', config('enums.transaction_types'));
            $table->text('description');
            $table->string('reference')->nullable();
            $table->string('source')->nullable();
            $table->date('date')->default(date('Y-m-d'));
            $table->string('proof_of_payment')->nullable();
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
        Schema::dropIfExists('cooperative_wallet_transactions');
    }
}
