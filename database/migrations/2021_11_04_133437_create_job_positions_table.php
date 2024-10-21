<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('position');
            $table->string('role')->nullable();
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->uuid('cooperative_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cooperative_id')->references('id')->on('cooperatives')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_positions');
    }
}
