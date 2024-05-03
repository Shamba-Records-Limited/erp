<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInternalRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_role_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('internal_role_id')->nullable();
            $table->uuid('cooperative_id');
            $table->smallInteger('can_view')->default(0);
            $table->smallInteger('can_create')->default(0);
            $table->smallInteger('can_edit')->default(0);
            $table->smallInteger('can_delete')->default(0);
            $table->smallInteger('can_download_report')->default(0);
            $table->uuid('created_by_user_id');
            $table->uuid('updated_by_user_id')->nullable();
            $table->foreign('internal_role_id')->references('id')->on('cooperative_internal_roles')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('cooperative_id')->references('id')->on('cooperatives')
                ->onUpdate('cascade')->onDelete('restrict');
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
        Schema::dropIfExists('internal_role_permissions');
    }
}
