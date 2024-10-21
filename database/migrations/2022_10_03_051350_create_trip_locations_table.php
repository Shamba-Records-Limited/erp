<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_id');
            $table->uuid('trip_id');
            $table->enum('type', ['DEPARTURE','ARRIVAL'])->default('DEPARTURE');
            $table->text('location');
            $table->dateTime('datetime');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('trip_id')->references('id')->on('trips')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('trip_locations');
    }
}
