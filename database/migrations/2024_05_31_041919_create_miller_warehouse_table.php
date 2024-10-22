<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_warehouse', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name")->unique();
            $table->string("location");
            $table->uuid("miller_id");
            $table->foreign('miller_id')->references('id')->on('millers')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('miller_warehouse');
    }
}
