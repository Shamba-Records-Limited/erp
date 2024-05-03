<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveProductionIdAndAddProductionHistoryIdOnProductionMaterialsTable extends Migration
{
    public function up()
    {
        Schema::table('production_materials', function (Blueprint $table) {
            $table->dropForeign('production_materials_production_id_foreign');
            $table->dropColumn('production_id');
            $table->uuid('production_history_id')->after('id');
            $table->foreign('production_history_id')->references('id')
                ->on('production_histories')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::table('production_materials', function (Blueprint $table) {
            $table->uuid('production_id');
            $table->foreign('production_id')->references('id')->on('productions')
                ->onUpdate('cascade')->onDelete('restrict');
            $table->dropForeign('production_materials_production_history_id_foreign');
            $table->dropColumn('production_history_id');
        });
    }
}
