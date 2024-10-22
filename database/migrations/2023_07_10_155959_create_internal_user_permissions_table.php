<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalUserPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_user_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('employee_id');
            $table->uuid('submodule_id');
            $table->uuid('cooperative_id');
            $table->smallInteger('can_view')->default(0);
            $table->smallInteger('can_create')->default(0);
            $table->smallInteger('can_edit')->default(0);
            $table->smallInteger('can_delete')->default(0);
            $table->smallInteger('can_download_report')->default(0);
            $table->uuid('created_by_user_id');
            $table->uuid('updated_by_user_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('submodule_id')->references('id')->on('system_submodules')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('created_by_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internal_user_permissions');
    }
}
