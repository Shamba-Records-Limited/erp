<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransportProviderVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_provider_vehicles', function (Blueprint $table) {
            $table->string('driver_name')->after('weight');
            $table->string('phone_no')->after('driver_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_provider_vehicles', function (Blueprint $table) {
            $table->dropColumn('driver_name');
            $table->dropColumn('phone_no');
        });
    }
}
