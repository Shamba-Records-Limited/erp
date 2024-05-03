<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cooperative_id');
            $table->enum('transport_type', ['OWN_VEHICLE', '3RD_PARTY'])->default('OWN_VEHICLE');
            $table->uuid('transport_provider_id')->nullable();
            $table->uuid('vehicle_id');
            $table->string('driver_name');
            $table->string('driver_phone_number');
            $table->string('load_type');
            $table->uuid('load_unit');
            $table->double('trip_distance', 11, 2, true)->comment('trip distance in  kms');
            $table->double('trip_cost_per_km', 11, 2, true);
            $table->double('trip_cost_per_kg', 11, 2, true);
            $table->double('trip_cost_total', 11, 2, true);
            $table->tinyInteger('status')->unsigned();
            $table->date('status_date');
            $table->string('status_comment')->default('---');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('transport_provider_id')->references('id')->on('transport_providers')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('load_unit')->references('id')->on('units')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('trips');
    }
}
