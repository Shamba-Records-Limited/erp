<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdToVetBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vet_bookings', function (Blueprint $table) {
            $table->uuid('service_id')->after('booking_type')->nullable();
            $table->foreign('service_id')->on('vet_services')->references('id')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vet_bookings', function (Blueprint $table) {
            $table->dropForeign('vet_bookings_service_id_foreign');
            $table->dropColumn('service_id');
        });
    }
}
