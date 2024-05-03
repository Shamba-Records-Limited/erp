<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesCooperativeInternalRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_cooperative_internal_roles', function (Blueprint $table) {
            $table->id();
            $table->uuid('module_id');
            $table->foreign('module_id')->on('system_modules')->references('id')->onDelete('restrict')->onUpdate('cascade');
            $table->uuid('role_id');
            $table->foreign('role_id')->on('cooperative_internal_roles')->references('id')->onDelete('restrict')->onUpdate('cascade');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->on('cooperatives')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules_cooperative_internal_roles');
    }
}
