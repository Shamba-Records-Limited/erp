<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUuidIdToFarmerCooperative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmer_cooperative', function (Blueprint $table) {
            //
            $table->uuid("id")->primary();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmer_cooperative', function (Blueprint $table) {
            //
            $table->dropPrimary("id");
            $table->dropColumn("id");
        });
    }
}
