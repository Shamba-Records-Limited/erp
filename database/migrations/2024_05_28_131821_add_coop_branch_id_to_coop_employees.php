<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoopBranchIdToCoopEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coop_employees', function (Blueprint $table) {
            //
            $table->uuid("coop_branch_id")->nullable();
            $table->foreign('coop_branch_id')->references('id')->on('coop_branches')
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
        Schema::table('coop_employees', function (Blueprint $table) {
            //
            $table->dropForeign("coop_employees_coop_branch_id_foreign");
            $table->dropColumn("coop_branch_id");
        });
    }
}
