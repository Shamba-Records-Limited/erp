<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('production_id');
            $table->double('quantity');
            $table->double('unit_price');
            $table->uuid('user_id');
            $table->smallInteger('expires')->default(0);
            $table->date('expiry_date')->nullable();
            $table->uuid('cooperative_id');
            $table->timestamps();

            $table->foreign('production_id')->references('id')->on('productions')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_histories');
    }
}
