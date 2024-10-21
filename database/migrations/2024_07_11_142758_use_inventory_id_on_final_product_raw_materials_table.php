<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UseInventoryIdOnFinalProductRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('final_product_raw_materials', function (Blueprint $table) {
            $table->dropColumn("material_type");
            $table->dropColumn("material_no");

            $table->uuid("milled_inventory_id");
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
        //
        Schema::table('final_product_raw_materials', function (Blueprint $table) {
            $table->dropForeign("final_product_raw_materials_milled_inventory_id_foreign");
            $table->dropColumn("milled_inventory_id");

            $table->string("material_type");
            $table->string("material_no");
        });
    }
}
