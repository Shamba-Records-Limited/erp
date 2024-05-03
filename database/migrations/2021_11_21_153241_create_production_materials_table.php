<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('raw_material_id');
            $table->uuid('production_id');
            $table->uuid('cooperative_id')->nullable();
            $table->double('cost', 13,4);
            $table->string('quantity');
            $table->uuid('unit_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('raw_material_id')->references('id')->on('raw_materials')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('production_id')->references('id')->on('productions')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('production_materials');
    }
}
