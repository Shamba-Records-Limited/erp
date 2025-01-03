<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerAuctionOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_auction_order_item', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("order_id");
            $table->foreign('order_id')->references('id')->on('miller_auction_order')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("product_category_id");
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('miller_auction_order_item');
    }
}
