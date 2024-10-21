<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnitIdFromCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            //
            $table->dropForeign("collections_unit_id_foreign");
            $table->dropColumn("unit_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            //
            $table->uuid("unit_id");
            $table->foreign("unit_id")->references("id")->on("units")
                ->onUpdate("cascade")
                ->onDelete("cascade");
        });
    }
}
