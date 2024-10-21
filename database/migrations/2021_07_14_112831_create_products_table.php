<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('name');
            $table->uuid("cooperative_id")->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('set null');
            $table->string('mode')->default('N/A');
            $table->double('sale_price');
            $table->double('paye')->default(0);
            $table->string('serial_number')->nullable();
            $table->string("image")->nullable();
            $table->uuid("category_id")->nullable();
            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')->onDelete('set null');
            $table->uuid("unit_id")->nullable();
            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
