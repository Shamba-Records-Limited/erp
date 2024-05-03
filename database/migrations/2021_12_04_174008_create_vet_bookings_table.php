<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVetBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vet_bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->dateTime('event_start');
            $table->dateTime('event_end');
            $table->string('event_name');
            $table->uuid("farmer_id")->nullable();
            $table->foreign('farmer_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->uuid("vet_id")->nullable();
            $table->foreign('vet_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->uuid("cooperative_id")->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
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
        Schema::dropIfExists('vet_bookings');
    }
}
