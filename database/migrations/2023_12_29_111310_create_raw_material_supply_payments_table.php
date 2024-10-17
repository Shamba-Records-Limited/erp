<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialSupplyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_material_supply_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('amount');
            $table->double('balance');
            $table->uuid('supply_history_id');
            $table->foreign('supply_history_id')->references('id')
                ->on('raw_material_supply_histories')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->references('id')
                ->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('raw_material_supply_payments');
    }
}
