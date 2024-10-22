<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeFarmerIndependent extends Migration
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
            $table->dropForeign("farmers_route_id_foreign");
            $table->dropColumn("route_id");
            $table->dropForeign("farmers_bank_branch_id_foreign");
            $table->dropColumn("bank_account");
            $table->dropColumn("bank_branch_id");
            // $table->string("location_id")->nullable()->change();
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
            $table->uuid("route_id")->nullable();
            $table->foreign("route_id")->references('id')->on('routes')->onUpdate('cascade')->onDelete('set null');
            $table->uuid('bank_branch_id')->nullable();
            $table->foreign('bank_branch_id')->references('id')->on('bank_branches')->onUpdate('cascade')->onDelete('set null');
            $table->string('bank_account')->nullable();
            // $table->string("location_id")->change();
        });
    }
}
