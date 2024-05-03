<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTripLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->uuid('location_id')->after('trip_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('CASCADE')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trip_locations', function (Blueprint $table) {
            $table->text('location')->after('type');
            $table->dropForeign('trip_locations_location_id_foreign');
            $table->dropColumn('location_id');
        });
    }
}
