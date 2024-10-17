<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMpesaFieldsToGroupLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_loan_repayments', function (Blueprint $table) {
            $table->string('merchant_request_id')->after('source')->nullable();
            $table->string('checkout_request_id')->after('merchant_request_id')->nullable();
            $table->index(['merchant_request_id','checkout_request_id'], 'mpesa_request_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_loan_repayments', function (Blueprint $table) {
            $table->dropIndex('mpesa_request_idx');
            $table->dropColumn(['merchant_request_id', 'checkout_request_id']);
        });
    }
}
