<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreDetailsToFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            $table->double('farm_size')->default(0);
            $table->integer('age')->default(18);
            $table->date('dob')->default(now());
            $table->enum('gender', config('enums.genders'))->default('M');
            $table->double('geolocation_lat')->nullable();
            $table->double('geolocation_long')->nullable();
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
            $table->dropColumn('farm_size');
            $table->dropColumn('age');
            $table->dropColumn('dob');
            $table->dropColumn('gender');
            $table->dropColumn('geolocation_lat');
            $table->dropColumn('geolocation_long');
        });
    }
}
