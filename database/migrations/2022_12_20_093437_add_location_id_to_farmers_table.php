<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('geolocation_lat');
            $table->dropColumn('geolocation_long');
            $table->uuid('location_id')->after('county')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')
                ->onUpdate('CASCADE')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->string('location')->after('county')->nullable();
            $table->double('geolocation_lat')->after('gender')->nullable();
            $table->double('geolocation_long')->after('geolocation_long')->nullable();
            $table->dropForeign('farmers_locations_location_id_foreign');
            $table->dropColumn('location_id');
        });
    }
}
