<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeProductsUnitsSystemWide extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            //
            $table->dropForeign("units_cooperative_id_foreign");
            $table->dropColumn("cooperative_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            //
            $table->uuid("cooperative_id")->nullable();
            $table->foreign("cooperative_id")->references("id")->on("cooperatives")->onUpdate("set null")->onDelete("set null");
        });
    }
}
