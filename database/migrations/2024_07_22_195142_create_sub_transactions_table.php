<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // similar to ledger transactions
        Schema::create('sub_transactions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            
            $table->uuid("transaction_id");
            $table->foreign('transaction_id')->references('id')->on('transactions');

            $table->string("credit_or_debit");  // CREDIT, DEBIT

            $table->string("credit_subject_type");  // ACCOUNT, MPESA_PHONE
            $table->string("credit_subject")->nullable();   // set if credit_subject_type is not ACCOUNT
            $table->uuid("credit_acc_id")->nullable();
            $table->foreign('credit_acc_id')->references('id')->on('accounts');

            $table->string("debit_subject_type");  // ACCOUNT, MPESA_PHONE
            $table->string("debit_subject")->nullable();   // set if debit_subject_type is not ACCOUNT
            $table->uuid("debit_acc_id")->nullable();
            $table->foreign('debit_acc_id')->references('id')->on('accounts');
            
            $table->timestamp("completed_at")->nullable();
            
            $table->timestamp("failed_at")->nullable();
            $table->timestamp("failed_reason")->nullable();

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
        Schema::dropIfExists('sub_transactions');
    }
}
