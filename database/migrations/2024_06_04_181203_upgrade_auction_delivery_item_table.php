<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpgradeAuctionDeliveryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('auction_order_delivery_item', function (Blueprint $table) {
            $table->dropForeign('auction_order_delivery_item_unit_id_foreign');
            $table->dropColumn('unit_id');

            $table->string("lot_number")->nullable();
            $table->foreign('lot_number')->references('lot_number')->on('lots')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('auction_order_delivery_item', function (Blueprint $table) {
            $table->dropForeign('auction_order_delivery_item_lot_number_foreign');
            $table->dropColumn('lot_number');

            $table->uuid("unit_id")->nullable();
            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
