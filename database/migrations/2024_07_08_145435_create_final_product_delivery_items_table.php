<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalProductDeliveryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_product_delivery_items', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("final_product_id");
            $table->foreign("final_product_id")->references("id")->on("final_products");

            $table->uuid("delivery_item_id");
            $table->foreign("delivery_item_id")->references("id")->on("auction_order_delivery_item");

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
        Schema::dropIfExists('final_product_delivery_items');
    }
}
