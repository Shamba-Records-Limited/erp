<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionStockTrackersTable extends Migration
{
    public function up()
    {
        Schema::create('production_stock_tracker', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('final_product_id');
            $table->date('date');
            $table->double('opening_quantity')->default(0);
            $table->double('opening_stock_value')->default(0);
            $table->double('closing_stock')->default(0);
            $table->double('closing_stock_value')->default(0);
            $table->uuid('cooperative_id');
            $table->timestamps();

            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('final_product_id')->references('id')->on('final_products')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_stock_tracker');
    }
}
