<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryNumberToAuctionOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_order_delivery', function (Blueprint $table) {
            //
            $table->string('delivery_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_order_delivery', function (Blueprint $table) {
            //
            $table->dropColumn('delivery_number');
        });
    }
}
