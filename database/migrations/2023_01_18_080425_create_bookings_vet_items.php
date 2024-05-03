<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsVetItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_vet_items', function (Blueprint $table) {
            $table->id();
            $table->uuid("vet_booking_id")->nullable();
            $table->foreign('vet_booking_id')->references('id')->on('vet_bookings')
                ->onUpdate('cascade')->onDelete('set null');
            $table->uuid("vet_item_id")->nullable();
            $table->foreign('vet_item_id')->references('id')->on('vet_items')
                ->onUpdate('cascade')->onDelete('set null');
            $table->double('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings_vet_items');
    }
}
