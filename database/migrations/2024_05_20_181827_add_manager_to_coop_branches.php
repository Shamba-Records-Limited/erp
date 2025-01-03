<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddManagerToCoopBranches extends Migration
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
            $table->uuid("manager_id")->nullable();
            $table->foreign('manager_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
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
            $table->dropForeign("coop_branches_manager_id_foreign");
            $table->dropColumn("manager_id");
        });
    }
}
