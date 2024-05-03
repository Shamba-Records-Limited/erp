<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('tag_name')->nullable();
            $table->uuid("breed_id")->nullable();
            $table->foreign('breed_id')->references('id')->on('breeds');
            $table->uuid("farmer_id")->nullable();
            $table->foreign('farmer_id')->references('id')->on('farmers');
            $table->uuid("cooperative_id")->nullable();
            $table->foreign('cooperative_id')->references('id')->on('cooperatives');
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
        Schema::dropIfExists('cows');
    }
}
