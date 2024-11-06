<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSystemTicketsModuleSubModuleImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('system_tickets', function (Blueprint $table) {
            //
            $table->string("module")->nullable();
            $table->string("submodule")->nullable();
            $table->string("link")->nullable();
            $table->string("image")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_tickets', function (Blueprint $table) {
            //
            $table->dropColumn("module");
            $table->dropColumn("submodule");
            $table->dropColumn("link");
            $table->dropColumn("image");
        });
    }
}
