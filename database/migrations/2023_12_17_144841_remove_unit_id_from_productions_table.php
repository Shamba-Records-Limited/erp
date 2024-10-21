<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnitIdFromProductionsTable extends Migration
{
    public function up()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropForeign('productions_unit_id_foreign');
            $table->dropColumn(['unit_id','expiry_date','production_count']);
            $table->uuid('cooperative_id')->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('production_materials', function (Blueprint $table) {
            $table->dropForeign('production_materials_unit_id_foreign');
            $table->dropColumn('unit_id');
        });
    }

    public function down()
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->date("expiry_date")->default(date('Y-m-d'));
            $table->integer("production_count");
            $table->uuid("unit_id")->nullable();
            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')->onDelete('set null');
            $table->dropForeign('productions_cooperative_id_foreign');
            $table->dropColumn('cooperative_id');
        });

        Schema::table('production_materials', function (Blueprint $table) {
            $table->uuid("unit_id")->nullable();
            $table->foreign('unit_id')->references('id')->on('units')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }
}
