<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('manufactured_product_id')->nullable();
            $table->uuid("collection_id")->nullable();
            $table->uuid("sales_id")->nullable();
            $table->double('amount')->default(0);
            $table->double('quantity')->default(1);
            $table->double('discount')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('manufactured_product_id')->references('id')->on('productions')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('collection_id')->references('id')->on('collections')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('sales_id')->references('id')->on('sales')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_items');
    }
}
