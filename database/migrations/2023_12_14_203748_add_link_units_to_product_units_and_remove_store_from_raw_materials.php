<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkUnitsToProductUnitsAndRemoveStoreFromRawMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign('raw_materials_store_id_foreign');
            $table->dropColumn(['units', 'store_id']);
            $table->uuid('unit_id')
                ->after('estimated_cost')
                ->nullable();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->dropForeign('raw_materials_unit_id_foreign');
            $table->dropColumn('unit_id');
            $table->string('units')->after('estimated_cost')->nullable();
            $table->uuid('store_id')
                ->after('units')
                ->nullable();
            $table->foreign('store_id')
                ->references('id')
                ->on('manufacturing_stores')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }
}
