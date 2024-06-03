<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerAuctionCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_auction_cart', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("miller_id");
            $table->foreign('miller_id')->references('id')->on('millers')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("cooperative_id");
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('miller_auction_cart');
    }
}
