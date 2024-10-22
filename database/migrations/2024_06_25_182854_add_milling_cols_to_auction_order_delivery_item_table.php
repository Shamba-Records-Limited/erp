<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMillingColsToAuctionOrderDeliveryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auction_order_delivery_item', function (Blueprint $table) {
            //
            $table->float("milled_quantity")->nullable();
            $table->float("waste_quantity")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auction_order_delivery_item', function (Blueprint $table) {
            //
            $table->dropColumn("milled_quantity");
            $table->dropColumn("waste_quantity");
        });
    }
}
