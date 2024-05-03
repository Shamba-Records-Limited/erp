<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeighBridgeEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weigh_bridge_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_id');
            $table->uuid('weigh_bridge_id');
            $table->uuid('trip_id');
            $table->uuid('trip_location_id');
            $table->double('weight', 11, 2, true)->nullable();
            $table->dateTime('datetime')->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('weigh_bridge_id')->references('id')->on('weigh_bridges')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('trip_id')->references('id')->on('trips')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('trip_location_id')->references('id')->on('trip_locations')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('weigh_bridge_events');
    }
}
