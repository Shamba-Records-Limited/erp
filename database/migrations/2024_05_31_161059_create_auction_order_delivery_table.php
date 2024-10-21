<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema;

class CreateAuctionOrderDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_order_delivery', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("order_id");
            $table->foreign('order_id')->references('id')->on('miller_auction_order')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("user_id"); // creator
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp("published_at")->nullable();  // this is to enable drafts
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
        Schema::dropIfExists('auction_order_delivery');
    }
}
