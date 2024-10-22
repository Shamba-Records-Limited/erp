<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToInvoicePaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->smallInteger('status')->default(\App\InvoicePayment::PAYMENT_STATUS_SUCCESS)->after('payment_platform');
        });
    }

    public function down()
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
