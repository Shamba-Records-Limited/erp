<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number');
            $table->uuid('cooperative_id');
            $table->uuid('user_id');
            $table->uuid('vehicle_type_id');
            $table->double('weight', 11, 2, true)->comment('vehicle weight in kgs');
            $table->tinyInteger('status')->unsigned();
            $table->date('status_date');
            $table->string('status_comment')->default('---');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
