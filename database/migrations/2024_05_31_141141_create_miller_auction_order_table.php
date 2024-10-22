<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerAuctionOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_auction_order', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("batch_number")->unique();
            $table->uuid("miller_id");
            $table->foreign('miller_id')->references('id')->on('millers')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("miller_warehouse_id")->nullable();
            $table->foreign('miller_warehouse_id')->references('id')->on('miller_warehouse')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("cooperative_id");
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp("published_at")->nullable();
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
        Schema::dropIfExists('miller_auction_order');
    }
}
