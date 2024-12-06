<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerAuctionOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_auction_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary UUID key
            $table->uuid('order_id'); // Foreign key to farmer_auction_orders table
            $table->foreign('order_id')->references('id')->on('farmer_auction_orders')->onDelete('cascade');
            $table->string('product_id')->nullable(); // Optional foreign key to products table
            $table->double('quantity', 8, 2); // Quantity with precision
            $table->double('selling_price', 8, 2); // Quantity with precision
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
        Schema::dropIfExists('farmer_auction_order_items');
    }
}
