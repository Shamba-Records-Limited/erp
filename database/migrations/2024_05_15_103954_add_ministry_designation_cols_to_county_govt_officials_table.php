<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinistryDesignationColsToCountyGovtOfficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('county_govt_officials', function (Blueprint $table) {
            //
            $table->string("ministry")->nullable();
            $table->string("designation")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('county_govt_officials', function (Blueprint $table) {
            //
            $table->dropColumn("ministry");
            $table->dropColumn("designation");
        });
    }
}
