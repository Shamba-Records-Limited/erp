<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMillerWarehouseAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miller_warehouse_admin', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("miller_warehouse_id");
            $table->foreign('miller_warehouse_id')->references('id')->on('miller_warehouse')->onUpdate('cascade')->onDelete('cascade');
            $table->uuid("user_id")->unique();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('miller_warehouse_admin');
    }
}
