<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToVetBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vet_bookings', function (Blueprint $table) {
            $table->enum('booking_type', config('enums.vet_service_types'))->after('reported_case_id')->default('Vet');
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
            $table->dropColumn('booking_type');
        });
    }
}
