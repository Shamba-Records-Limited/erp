<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("transaction_number")->unique();
            $table->string("amount_source"); // SELF, PARENT
            $table->float("amount")->nullable();
            $table->text("description");
            $table->string("type"); // COOPERATIVE_PAYMENT, FARMER_PAYMENT
            $table->string("status")->default("DRAFT"); // DRAFT, PENDING, READY, PROCESSING, VALIDATING, COMPLETE, FAILED

            $table->string("subject_type"); // LOT, BULK PAYMENT
            $table->uuid("subject_id");

            $table->string("sender_type");
            $table->string("sender_id");
            $table->uuid("sender_acc_id")->nullable();
            $table->foreign('sender_acc_id')->references('id')->on('accounts');

            $table->string("recipient_type");
            $table->string("recipient_id");
            $table->uuid("recipient_acc_id")->nullable();
            $table->foreign('recipient_acc_id')->references('id')->on('accounts');

            $table->string("channel_type")->nullable(); // RTGS, 
            $table->string("channel_ref")->nullable();

            $table->timestamp("published_at")->nullable();

            $table->string("failed_stage")->nullable();
            $table->string("failed_reason")->nullable();
            $table->timestamp("failed_at")->nullable();


            $table->timestamp("completed_at")->nullable();

            $table->uuid("created_by");
            $table->foreign("created_by")->references('id')->on('users');

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
        Schema::dropIfExists('transactions');
    }
}
