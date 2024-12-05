<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAuctionCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_auction_cart_items', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('cart_id', 36)->index();
            $table->string('product_id', 255)->index();
            $table->double('quantity', 8, 2);
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_auction_cart_items');
    }
}
