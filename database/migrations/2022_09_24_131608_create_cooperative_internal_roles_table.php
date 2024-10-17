<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCooperativeInternalRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooperative_internal_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('role');
            $table->uuid('cooperative_id');
            $table->foreign('cooperative_id')->on('cooperatives')->references('id')
                ->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cooperative_internal_roles');
    }
}
