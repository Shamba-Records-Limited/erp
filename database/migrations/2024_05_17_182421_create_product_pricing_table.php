<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPricingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_pricing', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("cooperative_id");
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("product_id");
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->double("min")->default(0);
            $table->double("max")->nullable();
            $table->uuid("unit_id")->nullable();
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('cascade')->onDelete('set null');
            $table->double("buying_price")->nullable();
            $table->double("buying_vat")->nullable();
            $table->double("selling_price")->nullable();
            $table->double("selling_vat")->nullable();
            $table->uuid("created_by_id");
            $table->foreign('created_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("updated_by_id")->nullable();
            $table->foreign('updated_by_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('product_pricing');
    }
}
