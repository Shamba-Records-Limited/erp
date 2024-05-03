<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportProviderVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transport_provider_vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number');
            $table->uuid('cooperative_id');
            $table->uuid('transport_provider_id');
            $table->uuid('vehicle_type_id');
            $table->double('weight', 11, 2, true)->comment('vehicle weight in kgs');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('transport_provider_id')->references('id')->on('transport_providers')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('transport_provider_vehicles');
    }
}
