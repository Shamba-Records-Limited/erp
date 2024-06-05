<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerAuctionCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_auction_cart_item', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('cart_id');
            $table->foreign('cart_id')->references('id')->on('miller_auction_cart')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string("lot_number");
            $table->foreign("lot_number")->references("lot_number")->on("lots")
                ->onUpdate("cascade")
                ->onDelete("cascade");
            $table->float("quantity");
            $table->uuid("unit_id");
            $table->foreign("unit_id")->references("id")->on("units")
                ->onUpdate("cascade")
                ->onDelete("cascade");
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
        Schema::dropIfExists('miller_auction_cart_item');
    }
}
