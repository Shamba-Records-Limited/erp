<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialSupplyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_material_supply_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('raw_material_id');
            $table->smallInteger('supply_type');
            $table->uuid('supplier_id')->nullable();
            $table->uuid('product_id')->nullable();
            $table->date('supply_date');
            $table->double('amount');
            $table->double('quantity');
            $table->string('details');
            $table->uuid('cooperative_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->foreign('raw_material_id')
                ->references('id')
                ->on('raw_materials')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->foreign('cooperative_id')
                ->references('id')
                ->on('cooperatives')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_material_supply_histories');
    }
}
