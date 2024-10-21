<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_permissions', function (Blueprint $table) {
            $table->id();
            $table->string("module");
            $table->string("sub_module");
            $table->string("action");
            $table->timestamps();

            $table->unique(["module", "sub_module", "action"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_permissions');
    }
}
