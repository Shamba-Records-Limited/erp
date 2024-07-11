<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMilledInvFkToPreMilledInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pre_milled_inventories', function (Blueprint $table) {
            $table->uuid("milled_inventory_id")->nullable();
            $table->foreign("milled_inventory_id")->references("id")->on("milled_inventories")
                ->onUpdate("cascade")
                ->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pre_milled_inventories', function (Blueprint $table) {
            //
            $table->dropForeign("pre_milled_inventories_milled_inventory_id_foreign");
            $table->dropColumn("milled_inventory_id");
        });
    }
}
