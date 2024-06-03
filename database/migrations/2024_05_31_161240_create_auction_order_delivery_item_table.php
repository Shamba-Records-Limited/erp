<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuctionOrderDeliveryItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_order_delivery_item', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("delivery_id");
            $table->foreign('delivery_id')->references('id')->on('auction_order_delivery')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("order_item_id");
            $table->foreign('order_item_id')->references('id')->on('miller_auction_order_item')->onUpdate('cascade')->onDelete('cascade');
            $table->float("quantity");
            $table->uuid("unit_id");
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('auction_order_delivery_item');
    }
}
