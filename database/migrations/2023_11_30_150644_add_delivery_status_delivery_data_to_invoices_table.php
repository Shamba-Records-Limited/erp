<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryStatusDeliveryDataToInvoicesTable extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->smallInteger('delivery_status')->default(\App\Invoice::DELIVERY_STATUS_DELIVERED)->after('date');
            $table->date('delivery_date')->nullable()->after('delivery_status');
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['delivery_status', 'delivery_date']);
        });
    }
}
