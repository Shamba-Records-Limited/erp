<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('raw_material_inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('raw_material_id');
            $table->double('quantity');
            $table->double('value');
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('raw_material_id')
                ->references('id')
                ->on('raw_materials')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::dropIfExists('raw_material_inventories');
    }
}
