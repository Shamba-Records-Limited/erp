<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountyColsToCoopBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coop_branches', function (Blueprint $table) {
            //
            $table->uuid("county_id")->nullable();
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
        Schema::table('coop_branches', function (Blueprint $table) {
            //
            $table->dropForeign("coop_branches_county_id_foreign");
            $table->dropColumn("county_id");
            $table->dropForeign("coop_branches_sub_county_id_foreign");
            $table->dropColumn("sub_county_id");
        });
    }
}
