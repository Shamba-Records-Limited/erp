<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpiredProductionProductsTable extends Migration
{
    public function up()
    {
        Schema::create('expired_production_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('production_history_id');
            $table->uuid('cooperative_id');
            $table->timestamps();

            $table->foreign('production_history_id')->references('id')->on('production_histories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }


    public function down()
    {
        Schema::dropIfExists('expired_production_products');
    }
}
