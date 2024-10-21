<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturingStoresTable extends Migration
{
    public function up()
    {
        Schema::create('manufacturing_stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('location');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cooperative_id')->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('manufacturing_stores');
    }
}
