<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePaymentPlatformColumnFromEnumToVarcharInInvoicePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('payment_platform');
        });

        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->string('payment_platform', 100)
                ->default(\App\InvoicePayment::PAYMENT_MODE_CASH)
                ->after('transaction_number');
            $table->string('merchant_request_id')
                ->nullable()->after('payment_platform')->index();
            $table->string('checkout_request_id')
                ->nullable()->after('merchant_request_id')->index();
            $table->index(['merchant_request_id', 'checkout_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn(['merchant_request_id', 'checkout_request_id']);
        });
    }
}
