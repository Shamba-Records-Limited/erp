<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMpesaFieldsToLoanInstallments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            $table->smallInteger('source')->after('status')->default(\App\LoanInstallment::WALLET_REPAYMENT_OPTION);
            $table->string('merchant_request_id')->after('source')->nullable();
            $table->string('checkout_request_id')->after('merchant_request_id')->nullable();
            $table->index(['merchant_request_id', 'checkout_request_id'], 'loan_installment_mpesa_request_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            $table->dropIndex('loan_installment_mpesa_request_idx');
            $table->dropColumn(['source','merchant_request_id', 'checkout_request_id']);
        });
    }
}
