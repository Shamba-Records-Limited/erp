<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLotNumToMillerAuctionOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('miller_auction_order_item', function (Blueprint $table) {
            //
            $table->string("lot_number")->nullable();
            $table->foreign("lot_number")->references("lot_number")->on("lots");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('miller_auction_order_item', function (Blueprint $table) {
            //
            $table->dropForeign("miller_auction_order_item_lot_number_foreign");
            $table->dropColumn("lot_number");
        });
    }
}
