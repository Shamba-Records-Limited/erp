<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmers_products', function (Blueprint $table) {
            $table->id();
            $table->uuid("farmer_id")->nullable();
            $table->foreign('farmer_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->uuid("product_id")->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('farmers_products');
    }
}
