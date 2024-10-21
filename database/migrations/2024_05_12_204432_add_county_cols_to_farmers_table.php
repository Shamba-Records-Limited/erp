<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountyColsToFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            //
            $table->dropColumn("county");
            $table->string("county_id")->nullable();
            $table->foreign('county_id')->references('id')->on('counties')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger("sub_county_id")->nullable();
            $table->foreign('sub_county_id')->references('id')->on('sub_counties')->onUpdate('cascade')->onDelete('cascade');
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
            //
            $table->string("county")->nullable();
            $table->dropForeign("farmers_county_id_foreign");
            $table->dropColumn("county_id");
            $table->dropColumn("sub_county_id");
        });
    }
}
