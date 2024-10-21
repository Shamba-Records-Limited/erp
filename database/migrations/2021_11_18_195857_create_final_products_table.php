<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('category_id')->nullable();
            $table->uuid('cooperative_id');
            $table->string('measuring_quantity')->nullable();
            $table->uuid('unit_id')->nullable();
            $table->double('production_cost',13,4)->nullable();
            $table->double('selling_price',13,4)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('unit_id')->references('id')->on('units')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('final_products');
    }
}
